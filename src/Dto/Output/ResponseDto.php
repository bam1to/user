<?php

namespace App\Dto\Output;

class ResponseDto
{

    /**
     * @param mixed $message
     * @param int $code
     * @param string[]|null $errors
     */
    public function __construct(
        private mixed $message = null,
        private int $code = 200,
        private ?array $errors = null
    ) {
    }

    public function getMessage(): mixed
    {
        return $this->message;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string[]|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

    public function addError(string $error): self
    {
        $this->errors[] = $error;

        return $this;
    }
}
