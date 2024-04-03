<?php

namespace App\Dto\Input;

use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationDto
{
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[Assert\Email(message: 'The email: {{ value }} is not valid')]
    private string $email;

    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private string $password;

    private ?string $phone = null;

    public function  getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone = null): self
    {
        $this->phone = $phone;

        return $this;
    }
}
