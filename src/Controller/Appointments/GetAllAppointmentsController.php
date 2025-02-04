<?php

namespace App\Controller\Appointments;

use App\Services\AppointmentService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/appointments', methods: ['GET'])]
class GetAllAppointmentsController
{
    public function __construct(private readonly AppointmentService $appointmentService, private readonly SerializerInterface $serializer)
    {
    }

    public function __invoke(): JsonResponse
    {
        $appointments = $this->appointmentService->getAppointments();

        return new JsonResponse($this->serializer->serialize($appointments, 'json'), Response::HTTP_OK, [], true);
    }
}
