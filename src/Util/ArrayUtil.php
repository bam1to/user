<?php

namespace App\Util;

class ArrayUtil
{
    /**
     * @param mixed[] $array
     * @return mixed[]
     */
    public function uniq(array $array): array
    {
        return array_values(array_unique($array, SORT_REGULAR));
    }
}
