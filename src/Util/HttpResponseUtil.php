<?php

namespace App\Util;

use Symfony\Component\HttpFoundation\Response;

class HttpResponseUtil
{
    /**
     * Check is valid exception http code
     * @param int $httpCode
     * @return bool
     */
    public function isValidHttpCode(int $httpCode): bool
    {
        return array_key_exists($httpCode, Response::$statusTexts);
    }
}
