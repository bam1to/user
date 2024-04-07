<?php

namespace App\Dto\Output;

class ResponseDto
{
    private mixed $message;

    private int $code;

    /**
     * @var string[]|null $errors
     */
    private ?array $errors;

    /**
     * @param mixed $message
     * @param int $code
     * @param string[]|null $errors
     */
    public function __construct(mixed $message, int $code = 200, ?array $errors = null)
    {
        $this->message = $message;
        $this->code = $code;
        $this->errors = $errors;
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

    /**
     * @return string[]|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

    /**
     * @param string[]|null $errors
     */
    public function setErrors(?array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    public function addError(string $error): self
    {
        $this->errors[] = $error;

        return $this;
    }
}
