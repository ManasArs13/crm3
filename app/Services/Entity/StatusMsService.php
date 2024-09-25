<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Option;
use App\Models\Status;
use App\Services\Api\MoySkladService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class StatusMsService implements EntityInterface
{
    private Option $options;
    private MoySkladService $service;
    private string $auth;
    private $client;


    public function __construct(
        Option $options,
        MoySkladService $service
    ) {
        $login = $options::where('code', '=', 'ms_login')->first()?->value;
        $password = $options::where('code', '=', 'ms_password')->first()?->value;
        $this->options = $options;
        $this->service = $service;
        $this->auth = base64_encode($login . ':' . $password);
        $this->client = new Client();
    }


    public function import(array $rows)
    {
        foreach ($rows["states"] as $row) {
            $entity = Status::firstOrNew(['ms_id' => $row['id']]);

            if ($entity->ms_id === null) {
                $entity->ms_id = $row['id'];
            }

            $entity->name = $row['name'];
            $entity->color="#".dechex($row["color"]);

            $entity->save();
        }

        $this->checking_for_availability_in_mc();
    }

    public function checking_for_availability_in_mc()
    {
        $url = 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/states/';

        $statuses = Status::all();

        foreach ($statuses as $status) {
            usleep(200);

            try {
                usleep(200);

                $response = $this->client->request('GET', $url . $status->ms_id, [
                    'headers' => [
                        'Accept-Encoding' => 'gzip',
                        'Authorization' => 'Basic ' . $this->auth
                    ],
                ]);

                $result = json_decode($response->getBody()->getContents(), true);

                if (isset($result["deleted"])) {
                    $status->delete();
                }
            } catch (RequestException  $e) {

                if ($e->getCode() == 404) {
                    $status->delete();

                    info($e->getMessage());
                    info('Статус №' . $status->ms_id . ' has been deleted!');
                }
            }
        }
    }

}
