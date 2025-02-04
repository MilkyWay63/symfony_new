<?php

namespace App\Entity;

use App\Repository\TimeSlotRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeSlotRepository::class)]
class TimeSlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Psychologist::class)]
    #[ORM\JoinColumn(name: "psychologist_id", nullable: false)]
    private Psychologist $psychologist;

    #[ORM\Column(name: "start_time",type: "datetime")]
    private DateTimeInterface $startTime;

    #[ORM\Column(name: "end_time", type: "datetime")]
    private DateTimeInterface $endTime;

    #[ORM\Column(name: "is_booked", type: "boolean")]
    private bool $isBooked = false;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPsychologist(): Psychologist
    {
        return $this->psychologist;
    }

    public function setPsychologist(Psychologist $psychologist): self
    {
        $this->psychologist = $psychologist;

        return $this;
    }

    public function getStartTime(): DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function isBooked(): bool
    {
        return $this->isBooked;
    }

    public function setBooked(bool $isBooked): self
    {
        $this->isBooked = $isBooked;

        return $this;
    }
}
