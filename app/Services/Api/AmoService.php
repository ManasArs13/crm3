<?php

namespace App\Services\Api;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\BaseRangeFilter;
use AmoCRM\Filters\ContactsFilter;
use AmoCRM\Filters\LeadsFilter;
use AmoCRM\Models\LeadModel;
use AmoCRM\OAuth2\Client\Provider\AmoCRM;
use App\Models\Option;
use App\Services\Entity\ContactAmoService;
use App\Services\Entity\OrderAmoService;
use App\Services\Entity\ProductAmoService;
use App\Services\Entity\StatusAmoService;
use App\Services\Entity\TalkAmoService;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Exception;

class AmoService
{
    public const LAST_DATE_CODE = 'last_date';

    protected $options;
    private $provider;
    private $apiClient;

    private $contactAmoService;
    private $orderAmoService;
    private $productAmoService;

    private $talkAmoService;

    private $statusAmoService;
    protected $uploadsTokenFile;

    public function __construct(
        Option $options,
        ContactAmoService $contactAmoService,
        OrderAmoService $orderAmoService,
        StatusAmoService $statusAmoService,
        ProductAmoService $productAmoService,
        TalkAmoService $talkAmoService
    ) {
        $this->contactAmoService = $contactAmoService;
        $this->orderAmoService = $orderAmoService;
        $this->statusAmoService = $statusAmoService;
        $this->productAmoService = $productAmoService;
        $this->talkAmoService = $talkAmoService;

        $options = Option::query()->where("module", "amo")->get();
        $this->uploadsTokenFile = 'token_amocrm_widget.json';

        foreach ($options as $option) {
            $this->options[$option->code] = $option->value;
        }
        $this->options["last_date"] = strtotime($this->options["last_date"]);
        $this->provider = new AmoCRM(["clientId" => $this->options["amo_widget_client_id"], "clientSecret" => $this->options["amo_widget_client_secret"], "redirectUri" => $this->options["redirect_uri"]]);
        $this->apiClient = new AmoCRMApiClient($this->options["amo_widget_client_id"], $this->options["amo_widget_client_secret"], $this->options["redirect_uri"]);

        $this->apiClient->onAccessTokenRefresh(function ($accessToken) {
            $data = [
                'accessToken' => $accessToken->getToken(),
                'expires' => $accessToken->getExpires(),
                'refreshToken' => $accessToken->getRefreshToken(),
                'baseDomain' => $this->options["base_domain"],
            ];

            $this->saveToken($data);
        });
        $this->provider->setBaseDomain($this->options["base_domain"]);
    }

    function getAccessToken()
    {
        try {
            $accessToken = $this->provider->getAccessToken('authorization_code', [
                'code' => $this->options['amo_widget_code'],
            ]);
            $data = [
                'accessToken' => $accessToken->getToken(),
                'expires' => $accessToken->getExpires(),
                'refreshToken' => $accessToken->getRefreshToken(),
                'baseDomain' => $this->options["base_domain"],
            ];
            $this->saveToken($data);

            return $this->getToken();
        } catch (Exception $exception) {
            Log::error(__METHOD__ . ' exception request token error (Invalid access token):' . $exception->getMessage());
        }
    }


    /**
     * @param array $accessToken
     */
    function saveAmoToken($accessToken, $uploadsTokenFile)
    {
        if (
            isset($accessToken)
            && isset($accessToken['accessToken'])
            && isset($accessToken['refreshToken'])
            && isset($accessToken['expires'])
            && isset($accessToken['baseDomain'])
        ) {

            $data = [
                'accessToken' => $accessToken['accessToken'],
                'expires' => $accessToken['expires'],
                'refreshToken' => $accessToken['refreshToken'],
                'baseDomain' => $accessToken['baseDomain'],
            ];

            file_put_contents($uploadsTokenFile, json_encode($data));
        } else {
            Log::error(__METHOD__ . ' exception request token error (Invalid access token )');
        }
    }

    /**
     * @param array $accessToken
     */
    function saveToken($accessToken)
    {
        if (
            isset($accessToken)
            && isset($accessToken['accessToken'])
            && isset($accessToken['refreshToken'])
            && isset($accessToken['expires'])
            && isset($accessToken['baseDomain'])
        ) {
            $data = [
                'accessToken' => $accessToken['accessToken'],
                'expires' => $accessToken['expires'],
                'refreshToken' => $accessToken['refreshToken'],
                'baseDomain' => $accessToken['baseDomain'],
            ];

            file_put_contents(base_path($this->uploadsTokenFile), json_encode($data));
        } else {
            Log::error(__METHOD__ . ' exception request token error (Invalid access token )');
        }
    }

    /**
     * @return AccessToken
     */
    function getToken($isCode = false)
    {
        if ($isCode) {
            return $this->getAccessToken();
        }

        $accessToken = json_decode(file_get_contents(base_path($this->uploadsTokenFile)), true);

        if (
            isset($accessToken)
            && isset($accessToken['accessToken'])
            && isset($accessToken['refreshToken'])
            && isset($accessToken['expires'])
            && isset($accessToken['baseDomain'])
        ) {

            $accessToken = new AccessToken([
                'access_token' => $accessToken['accessToken'],
                'refresh_token' => $accessToken['refreshToken'],
                'expires' => $accessToken['expires'],
                'baseDomain' => $accessToken['baseDomain'],
            ]);

            return $accessToken;
        } else {
            return $this->getAccessToken();
        }
    }

    private function isExpiredToken(AccessToken $accessToken): AccessToken
    {
        return $accessToken;
    }

    /**
     * @throws AmoCRMApiException
     * @throws AmoCRMMissedTokenException
     * @throws AmoCRMoAuthApiException
     */
    public function getContacts(): void
    {
        $accessToken = $this->getToken();
        $accessToken = $this->isExpiredToken($accessToken);
        $baseDomain = $accessToken->getValues()['baseDomain'];

        $this->apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['baseDomain']);

        //Создадим фильтр по id сделки и ответственному пользователю
        $filter = new ContactsFilter();

        $range = new BaseRangeFilter();
        $range->setFrom($this->options["last_date"]);
        $time = time();
        $range->setTo((int)$time);
        $filter->setUpdatedAt($range);
        $filter->setLimit(250);

        $contacts = [];

        try {
            $contacts = $this->apiClient->contacts()->get($filter);
            $this->contactAmoService->import([$contacts]);

            $i = 2;
            while ($contacts->getNextPageLink() != null) {
                $filter->setPage($i);
                $contacts = $this->apiClient->contacts()->get($filter);
                $this->contactAmoService->import([$contacts]);
                $i++;
            }
        } catch (AmoCRMApiNoContentException $exception) {
            Log::error(__METHOD__ . ' getContacts:' . $exception->getMessage());
        }
    }

    public function getContactsAll(): void
    {
        $accessToken = $this->getToken();
        $accessToken = $this->isExpiredToken($accessToken);
        $baseDomain = $accessToken->getValues()['baseDomain'];

        $this->apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['baseDomain']);

        //Создадим фильтр по id сделки и ответственному пользователю
        $filter = new ContactsFilter();
        $filter->setLimit(250);

        $contacts = [];

        try {
            $contacts = $this->apiClient->contacts()->get($filter);
            $this->contactAmoService->import([$contacts]);

            $i = 2;
            while ($contacts->getNextPageLink() != null) {
                $filter->setPage($i);
                $contacts = $this->apiClient->contacts()->get($filter);
                $this->contactAmoService->import([$contacts]);
                $i++;
            }
        } catch (AmoCRMApiNoContentException $exception) {
            Log::error(__METHOD__ . ' getContacts:' . $exception->getMessage());
        }
    }

    public function updateContacts(): void
    {
        $accessToken = $this->getToken();
        $accessToken = $this->isExpiredToken($accessToken);
        $baseDomain = $accessToken->getValues()['baseDomain'];

        $this->apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['baseDomain']);

        //Создадим фильтр по id сделки и ответственному пользователю
        $filter = new ContactsFilter();

        $range = new BaseRangeFilter();
        $range->setFrom($this->options["last_date"]);
        $time = time();
        $range->setTo((int)$time);
        $filter->setUpdatedAt($range);
        $filter->setLimit(250);

        $contacts = [];

        try {
            $contacts = $this->apiClient->contacts()->get($filter);
            $this->contactAmoService->update($contacts);

            $i = 2;
            while ($contacts->getNextPageLink() != null) {
                $filter->setPage($i);
                $contacts = $this->apiClient->contacts()->get($filter);
                $this->contactAmoService->update($contacts);
                $i++;
            }
            echo $i;
        } catch (AmoCRMApiNoContentException $exception) {
            Log::error(__METHOD__ . ' getContacts:' . $exception->getMessage());
        }
    }

    public function getProducts(): void
    {
        $accessToken = $this->getToken();
        $accessToken = $this->isExpiredToken($accessToken);
        $this->apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['baseDomain']);

        //Создадим фильтр по id сделки и ответственному пользователю
        $filter = new ContactsFilter();

        $range = new BaseRangeFilter();
        $range->setFrom($this->options["last_date"]);
        $time = time();
        $range->setTo((int)$time);
        $filter->setUpdatedAt($range);
        $filter->setLimit(250);

        try {
            $catalogId = $this->apiClient->catalogs()->get($filter)->first()->getId();
            $products = $this->apiClient->catalogElements($catalogId)->get($filter);
            $this->productAmoService->import([$products]);
            $i = 2;
            while ($products->getNextPageLink() !== null) {
                $filter->setPage($i);
                $products = $this->apiClient->catalogElements($catalogId)->get($filter);
                $this->productAmoService->import([$products]);
                $i++;
            }
        } catch (AmoCRMApiNoContentException $exception) {
            Log::error(__METHOD__ . ' getContacts:' . $exception->getMessage());
        }
    }
    /**
     * @throws AmoCRMMissedTokenException
     */
    public function getStatuses(): array
    {
        $accessToken = $this->getToken();
        $accessToken = $this->isExpiredToken($accessToken);
        $baseDomain = $accessToken->getValues()['baseDomain'];

        $this->apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['baseDomain']);

        $statusesService = $this->apiClient->statuses($this->options["pipeline_id"]);

        $statusesCollection = [];

        try {
            $statusesCollection[] = $statusesService->get();
            $this->statusAmoService->import($statusesCollection);
        } catch (AmoCRMApiException $exception) {
            Log::error(__METHOD__ . ' setLead:' . $exception->getMessage());
        }
        return $statusesCollection;
    }

    public function getLeadsWithContacts()
    {
        $accessToken = $this->getToken();
        $accessToken = $this->isExpiredToken($accessToken);
        $baseDomain = $accessToken->getValues()['baseDomain'];

        $this->apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['baseDomain']);

        //Создадим фильтр по id сделки и ответственному пользователю
        $filter = new LeadsFilter();

        $range = new BaseRangeFilter();
        $range->setFrom($this->options["last_date"]);
        $time = time();
        $range->setTo((int)$time);
        $filter->setUpdatedAt($range);
        $filter->setLimit(250);

        $leads = [];

        try {

            $leads[] = $this->apiClient->leads()->get($filter, [LeadModel::CONTACTS]);
            $this->orderAmoService->import($leads);
            $i = 2;
            while ($leads[0]->getNextPageLink() != null) {
                $filter->setPage($i);
                $leads[] = $this->apiClient->leads()->get($filter, [LeadModel::CONTACTS]);
                $this->orderAmoService->import($leads);
                $i++;
            }
        } catch (AmoCRMApiNoContentException $exception) {
            Log::error(__METHOD__ . ' setLead:' . $exception->getMessage());
        }
    }

    public function getTalks()
    {
        $accessToken = $this->getToken();
        $accessToken = $this->isExpiredToken($accessToken);
        $baseDomain = $accessToken->getValues()['baseDomain'];

        $this->apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['baseDomain']);

        // $filter = new ContactsFilter();
        // $range = new BaseRangeFilter();
        // $range->setFrom($this->options["last_date"]);
        // $time = time();
        // $range->setTo((int)$time);
        // $filter->setUpdatedAt($range);
        // $filter->setLimit(250);

        $talkCollection = [];

        try {
            $talkCollection = $this->apiClient->talks()->get();
            $this->talkAmoService->import([$talkCollection]);

            // $i = 2;

            // while ($talkCollection->getNextPageLink() != null) {
            //     $filter->setPage($i);
            //     $talkCollection = $this->apiClient->talks()->get($filter);
            //     $this->talkAmoService->import([$talkCollection]);
            //     $i++;
            // }
        } catch (AmoCRMApiException $exception) {
            Log::error(__METHOD__ . ' setLead:' . $exception->getMessage());
        }

        return $talkCollection;
    }
}
