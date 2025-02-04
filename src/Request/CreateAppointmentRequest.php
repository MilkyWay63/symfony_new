<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateAppointmentRequest
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $timeSlot;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $clientName;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $clientEmail;

    public function getTimeSlot(): int
    {
        return $this->timeSlot;
    }

    public function setTimeSlot(int $timeSlot): CreateAppointmentRequest
    {
        $this->timeSlot = $timeSlot;

        return $this;
    }

    public function getClientName(): string
    {
        return $this->clientName;
    }

    public function setClientName(string $clientName): CreateAppointmentRequest
    {
        $this->clientName = $clientName;

        return $this;
    }

    public function getClientEmail(): string
    {
        return $this->clientEmail;
    }

    public function setClientEmail(string $clientEmail): CreateAppointmentRequest
    {
        $this->clientEmail = $clientEmail;

        return $this;
    }
}
