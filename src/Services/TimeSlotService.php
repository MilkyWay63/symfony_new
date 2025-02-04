<?php

namespace App\Services;

use App\Entity\Psychologist;
use App\Entity\TimeSlot;
use App\Repository\TimeSlotRepository;
use App\Request\AddTimeSlotRequest;
use App\Request\UpdateTimeSlotRequest;
use DateTimeImmutable;
use Pagerfanta\Pagerfanta;

class TimeSlotService
{
    public function __construct(private readonly TimeSlotRepository $timeSlotRepository)
    {
    }

    /**
     * @return Pagerfanta $timeSlots
     */
    public function getPsychologistsAvailableTimeSlots(Psychologist $psychologist): Pagerfanta
    {
        return $this->timeSlotRepository->findPsychologistAvailableTimeSlots($psychologist);

    }

    public function addPsychologistTimeSlot(Psychologist $psychologist, AddTimeSlotRequest $request): TimeSlot
    {
        $timeSlot = (new TimeSlot())
            ->setEndTime(new DateTimeImmutable($request->getEndTime()))
            ->setStartTime(new DateTimeImmutable($request->getStartTime()))
            ->setPsychologist($psychologist)
        ;
        $this->timeSlotRepository->save($timeSlot);

        return $timeSlot;
    }

    public function removeTimeSlot(TimeSlot $timeSlot): void
    {
        $this->timeSlotRepository->remove($timeSlot);
    }

    public function updateTimeSlot(TimeSlot $timeSlot, UpdateTimeSlotRequest $request): TimeSlot
    {
        $timeSlot->setBooked($request->isIsBooked());
        $this->timeSlotRepository->save($timeSlot);

        return $timeSlot;
    }
}
