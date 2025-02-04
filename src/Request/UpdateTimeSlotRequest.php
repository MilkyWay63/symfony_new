<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateTimeSlotRequest
{
    #[Assert\NotBlank]
    #[Assert\Type("boolean")]
    public bool $is_booked;

    public function isIsBooked(): bool
    {
        return $this->is_booked;
    }

    public function setIsBooked(bool $is_booked): UpdateTimeSlotRequest
    {
        $this->is_booked = $is_booked;

        return $this;
    }
}