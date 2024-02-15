<?php

namespace App\Services\Api;


use App\Models\Option;
use GuzzleHttp\Client;
use App\Contracts\EntityInterface;
use GuzzleHttp\Exception\RequestException;

class MoySkladService
{
    public const MS_DATE_BEGIN_CHANGE = 'ms_date_begin_change';
    private string $auth;
    private int $limit;
    private $client;

    public function __construct(Option $options)
    {
        $login = $options::where('code', '=', 'ms_login')->first()?->value;
        $password = $options::where('code', '=', 'ms_password')->first()?->value;
        $this->limit = (int)$options::where('code', '=', 'ms_limit')->first()?->value;
        $this->auth = base64_encode($login . ':' . $password);
        $this->client = new Client();
    }

    //    /**
    //     * @param string $url Урл для получения данных из "Мой Склад"
    //     * @param EntityInterface $repository Репозиторий модели для синхронизации
    //     * @param array $filters Массив фильтрации для выгрузки
    //     * @param string $expand
    //     * @param int $attr
    //     * @param bool $isImport
    //     * @return string
    //     */
    public function createUrl(
        string            $url,
        EntityInterface $repository,
        array             $filters = [],
        string            $expand = "",
        int               $attr = 0,
        bool              $isImport = true
    ) {
        $offset = 0;
        $step = 0;
        $count = 1;
        $strGuids = "";

        if (!empty($filters)) {
            $strGuids = $this->getFilter($filters);
        }
        while ($count > 0) {
            $_url = $url;
            if ($attr != -1)
                $_url .= '?limit=' . $this->limit . '&offset=' . $offset;
            if ($strGuids != "")
                $_url .= "&filter=" . $strGuids;
            if ($expand != "")
                $_url .= "&expand=" . $expand;

            $rows = $this->actionGetRowsFromJson($_url, false);

            if ($isImport) {
                $repository->import($rows);
            }

            $step++;
            $offset = $step * $this->limit;

            switch ($attr) {
                case 0:
                    $count = $rows["meta"]["size"] - $offset;
                    break;
                case 1:
                    $count = $rows["attributes"]["meta"]["size"] - $offset;
                    break;
                case -1:
                    $count = 0;
            }
        }
        return 0;
    }

    public function actionGetRowsFromJson(string $url, bool $rows = true, $downloadPath = '')
    {
        try {
            usleep(200000);
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Accept-Encoding' => 'gzip',
                    'Authorization' => 'Basic ' . $this->auth
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            if (isset($result["errors"])) {
                $code = "";
                foreach ($result["errors"] as $error) {
                    $code .= $error["code"] . " " . $error["error"] . "; ";
                }
            }

            if ($rows) {
                return $result["rows"];
            }

            if ($downloadPath != '') {
                return file_put_contents($downloadPath, $response->getBody());
            }
            //        sleep(1);
            return $result;
        } catch (RequestException  $e) {
            info($e->getMessage());
            return false;
        }
    }

    public function getFilter(array $filters)
    {
        $filterString = "";
        $i = 0;
        foreach ($filters as $filterkey => $filtervalue) {
            $filterString .= ($i > 0) ? ";" : "";
            switch ($filterkey) {
                case "productFolder":
                    $filterString .= $filtervalue;
                    break;
                default:
                    if (is_array($filtervalue)) {
                        foreach ($filtervalue as $key => $value) {
                            $filterString .= $filterkey . "=" . $value;
                            if ($key != count($filtervalue) - 1)
                                $filterString .= ";";
                        }
                    } else {
                        $filterString = $filterkey . $filtervalue;
                    }
                    break;
            }
            ++$i;
        }

        return $filterString;
    }


    public function actionPutRowsFromJson($url, $array)
    {
        try {
            $response = $this->client->request("PUT", $url, [
                'headers' => [
                    'Accept-Encoding' => 'gzip',
                    'Authorization' => 'Basic ' . $this->auth
                ],
                'json' => $array
            ]);


            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                return true;
            } else {
                return false;
            }
        } catch (RequestException  $e) {
            info($e->getMessage());
            return false;
        }
    }

    public function actionPostRowsFromJson($url, $array)
    {
        try {
            $response = $this->client->request("POST", $url, [
                'headers' => [
                    'content-type' => 'application/json',
                    'Accept-Encoding' => 'gzip',
                    'user-agent' => 'My User Agent',
                    'Authorization' => 'Basic ' . $this->auth
                ],
                'json' => $array

            ]);

            $statusCode = $response->getStatusCode();


            if ($statusCode == 200) {

                $content = json_decode($response->getBody()->getContents());
                $array = ["isGood" => true, "id" => $content->id];

                if (isset($content->name)) {
                    $array["name"] = $content->name;
                }
                return $array;
            } else {
                return ["isGood" => false, "errors" => $response->getContent(false)];
            }
            return false;
        } catch (RequestException  $e) {
            info($e->getMessage());
            return false;
        }
    }
}
