<?php

namespace App\Controller\Appointments;

use App\Request\CreateAppointmentRequest;
use App\Services\AppointmentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/appointments', methods: ['POST'])]
class CreateAppointmentController extends AbstractController
{
    public function __construct(private readonly AppointmentService $appointmentService, private readonly SerializerInterface $serializer)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $createAppointmentRequest = $this->serializer->deserialize($request->getContent(), CreateAppointmentRequest::class, 'json');

        $appointment = $this->appointmentService->createAppointment($createAppointmentRequest);


        return new JsonResponse($this->serializer->serialize($appointment, 'json'), Response::HTTP_CREATED, [], true);
    }
}
