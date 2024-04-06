<?php

namespace App\Util;

class ArrayUtil
{
    public function uniq(array $array): array
    {
        return array_values(array_unique($array, SORT_REGULAR));
    }
}
