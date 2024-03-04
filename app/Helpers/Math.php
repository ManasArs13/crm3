<?php

namespace App\Helpers;

class Math
{
    public static function rounding_up_to($num, $x = 5)
    {
        if ($num <= $x/2) {
            return $num;
        } else {
            if ($num % $x < $x / 2) {
                return $num - ($num % $x);
            } else {
                return $num + ($x - ($num % $x));
            }
        }
    }
}
