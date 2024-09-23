<?php

namespace App\Services\Api;


use App\Models\Option;
use GuzzleHttp\Client;
use App\Contracts\EntityInterface;
use Exception;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;

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
        EntityInterface $repository=null,
        array             $filters = [],
        string            $expand = "",
        int               $attr = 0,
        bool              $isImport = true
    ) {
        $offset = 0;
        $step = 0;
        $count = 1;
        $strGuids = "";
        $size=0;

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
                case 2:// limit
                    $count=0;
                    $size=$rows["meta"]["size"];
                    break;
            }
        }
        return $size;
    }

    public function actionGetRowsFromJson(string $url, bool $rows = true, $downloadPath = '')
    {
        try {
            usleep(200);
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
            info($e->getResponse()->getBody()->getContents());
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
                return json_decode($response->getBody()->getContents());
            } else {
                return false;
            }
        } catch (ClientException  $e) {
            // var_dump($e->getResponse()->getBody()->getContents());
            info($e->getResponse()->getBody()->getContents());
            return  Psr7\Message::toString($e->getResponse());
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
                return json_decode($response->getBody()->getContents());
            } else {
                return ["isGood" => false, "errors" => $response->getContent(false)];
            }
            return false;
        } catch (ClientException $e) {

            // echo Psr7\Message::toString($e->getRequest());
            // echo Psr7\Message::toString($e->getResponse());
            // var_dump($e->getResponse()->getBody()->getContents());
           // $rrr=var_export($e->getResponse()->getBody()->getContents(), true);
            info($e->getResponse()->getBody()->getContents());
            return  Psr7\Message::toString($e->getResponse());
        }
    }
}
