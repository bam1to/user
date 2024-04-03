<?php

namespace App\Dto\Output;

use JsonSerializable;

class ResponseDto implements JsonSerializable
{
    private string $message;

    private int $code;

    public function __construct(string $message, int $code = 200)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
