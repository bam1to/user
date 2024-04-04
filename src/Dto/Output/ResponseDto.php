<?php

namespace App\Dto\Output;

class ResponseDto
{
    private mixed $message;

    private int $code;

    private ?string $error;

    public function __construct(mixed $message, int $code = 200, ?string $error = null)
    {
        $this->message = $message;
        $this->code = $code;
        $this->error = $error;
    }

    public function getMessage(): mixed
    {
        return $this->message;
    }

    public function setMessage(mixed $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(?string $error): self
    {
        $this->error = $error;
        return $this;
    }
}
