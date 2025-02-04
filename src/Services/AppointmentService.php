<?php

namespace App\Services;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use App\Repository\TimeSlotRepository;
use App\Request\CreateAppointmentRequest;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AppointmentService
{
    public function __construct(
        private AppointmentRepository $appointmentRepository,
        private TimeSlotRepository $timeSlotRepository
    ) {
    }

    /**
     * @return Pagerfanta $appointments
     */
    public function getAppointments(): Pagerfanta
    {
        return $this->appointmentRepository->findUpcomingAppointments();

    }

    public function createAppointment(CreateAppointmentRequest $request): JsonResponse|Appointment
    {
        $timeSlot = $this->timeSlotRepository->find($request->getTimeSlot());
        if (!$timeSlot || $timeSlot->isBooked()) {
            return new JsonResponse(['error' => 'Invalid or already booked time slot'], Response::HTTP_BAD_REQUEST);
        }

        $appointment = (new Appointment())
        ->setClientName($request->getClientName())
        ->setClientEmail($request->getClientEmail())
        ;

        $appointment->setTimeSlot($timeSlot);
        $timeSlot->setBooked(true);

        $this->appointmentRepository->save($appointment);
        $this->timeSlotRepository->save($timeSlot);

        return $appointment;
    }
}
