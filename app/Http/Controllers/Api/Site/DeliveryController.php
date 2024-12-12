<?php
namespace App\Http\Controllers\Api\Site;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery;

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
