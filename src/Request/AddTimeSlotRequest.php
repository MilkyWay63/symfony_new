<?php

namespace App\Request;

use App\Entity\Psychologist;
use Symfony\Component\Validator\Constraints as Assert;

class AddTimeSlotRequest
{
    #[Assert\NotBlank]
    public Psychologist $psychologist;

    #[Assert\NotBlank]
    #[Assert\DateTime(format: 'Y-m-d\TH:i:s')]
    public string $startTime;

    #[Assert\NotBlank]
    #[Assert\DateTime(format: 'Y-m-d\TH:i:s')]
    public string $endTime;

    public function getPsychologist(): Psychologist
    {
        return $this->psychologist;
    }

    public function setPsychologist(Psychologist $psychologist): AddTimeSlotRequest
    {
        $this->psychologist = $psychologist;

        return $this;
    }

    public function getStartTime(): string
    {
        return $this->startTime;
    }

    public function setStartTime(string $startTime): AddTimeSlotRequest
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): string
    {
        return $this->endTime;
    }

    public function setEndTime(string $endTime): AddTimeSlotRequest
    {
        $this->endTime = $endTime;

        return $this;
    }
}
