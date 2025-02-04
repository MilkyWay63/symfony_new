<?php

namespace App\Controller\Psychologists;

use App\Entity\Psychologist;
use App\Services\TimeSlotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/psychologists/{psychologist}/time-slots', methods: ['GET'])]
class ViewTimeSlotsController extends AbstractController
{
    public function __construct(private TimeSlotService $timeSlotService, private SerializerInterface $serializer)
    {
    }

    public function __invoke(Psychologist $psychologist): JsonResponse
    {
        $timeSlots = $this->timeSlotService->getPsychologistsAvailableTimeSlots($psychologist);

        return new JsonResponse($this->serializer->serialize($timeSlots, 'json'), Response::HTTP_OK, [], true);
    }
}
