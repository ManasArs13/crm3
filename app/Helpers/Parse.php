<?php

namespace App\Helpers;

use AmoCRM\Filters\BaseRangeFilter;

class Parse
{
    public function parseIntOrIntRangeFilter($value)
    {
        if ($value instanceof BaseRangeFilter) {
            $value = $value->toFilter();
        } elseif (!is_int($value) || $value < 0) {
            $value = null;
        }

        return $value;
    }
}
