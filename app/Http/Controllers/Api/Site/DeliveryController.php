<?php
namespace App\Http\Controllers\Api\Site;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;

class DeliveryController extends Controller
{

    public function getByName(Request $request)
    {
        $deliveries = Delivery::query()
        ->where('name', 'LIKE', '%'.$request->query('q').'%')
        ->orderBy("name")->paginate(10,  ['*'], 'page', request()->query('page'));

        return response()->json($deliveries);
    }

    public function getBetonPrice($id)
    {
        return  Delivery::where("id", $id)->first()?->betonprice;
    }

    public function setKmFact()
    {
        $deliveries = Delivery::chunkById(100, function ($deliveries) {
            foreach ($deliveries as $delivery) {
                $φA=45.12410907456747;
                $λA=34.01251650000001;

                if ($delivery->coords!=null){
                    $coords=explode(",", $delivery->coords);

                    $φB=$coords[0];
                    $λB=$coords[1];

                    $delivery->km_fact2=$this->calculateTheDistance($φA, $λA, $φB, $λB);
                    $delivery->save();
                }
            }
        });
    }

    public function setKmFactPath()
    {
        $deliveries = Delivery::chunkById(100, function ($deliveries) {
            foreach ($deliveries as $delivery) {
                if ($delivery->coords!=null){
                        $φA=45.12410907456747;
                        $λA=34.01251650000001;

                        $coords=explode(",", $delivery->coords);
                        $φB=$coords[0];
                        $λB=$coords[1];

                        $url='https://routing.api.2gis.com/get_dist_matrix?key=9583a383-181b-47d3-a5df-578e08cf5e9a&version=2.0';
                        $client = new Client();


                        $array["points"]=[
                            ["lat"=>$φA, "lon"=>$λA],
                            ["lat"=>$φB, "lon"=>$λB]
                        ];

                        $array["sources"]=[0];
                        $array["targets"]=[1];
                        $array["type"]="shortest";
                        $array["transport"]="truck";
                        $array["vehicle_speed_limit"]= 90;

                        $response = $client->request("POST", $url, [
                            'headers' => [
                                'content-type' => 'application/json',
                            ],
                            'json' => $array
                        ]);

                        $statusCode = $response->getStatusCode();

                        if ($statusCode == 200) {
                            $result=json_decode($response->getBody()->getContents());

                            $delivery->km_fact=round($result->routes[0]->distance/1000);

                            $delivery->duration_min=round($result->routes[0]->duration/60);
                            $delivery->save();
                        } else {
                            print_r($response->getContent(false));
                        }
                }
            }
        });
    }

        // Радиус земли
            /*
            * Расстояние между двумя точками
            * $φA, $λA - широта, долгота 1-й точки,
            * $φB, $λB - широта, долгота 2-й точки
            *
            */
     function calculateTheDistance ($φA, $λA, $φB, $λB) {


            $EARTH_RADIUS=6372795;
            // перевести координаты в радианы
            $lat1 = $φA * M_PI / 180;
            $lat2 = $φB * M_PI / 180;
            $long1 = $λA * M_PI / 180;
            $long2 = $λB * M_PI / 180;

            // косинусы и синусы широт и разницы долгот
            $cl1 = cos($lat1);
            $cl2 = cos($lat2);
            $sl1 = sin($lat1);
            $sl2 = sin($lat2);
            $delta = $long2 - $long1;
            $cdelta = cos($delta);
            $sdelta = sin($delta);

            // вычисления длины большого круга
            $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
            $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;

            //
            $ad = atan2($y, $x);
            $dist = round($ad * $EARTH_RADIUS/1000);

            return $dist;
    }
}
