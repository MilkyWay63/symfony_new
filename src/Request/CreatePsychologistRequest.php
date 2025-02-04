<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePsychologistRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): CreatePsychologistRequest
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): CreatePsychologistRequest
    {
        $this->email = $email;

        return $this;
    }
}