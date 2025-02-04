<?php

namespace App\Controller\Psychologists;

use App\Entity\Psychologist;
use App\Request\AddTimeSlotRequest;
use App\Services\TimeSlotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/psychologists/{psychologist}/time-slots', methods: ['POST'])]
class AddTimeSlotController extends AbstractController
{
    public function __construct(
        private readonly TimeSlotService $timeSlotService,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function __invoke(Psychologist $psychologist, Request $request): JsonResponse
    {
        $addTimeSlotRequest =  $this->serializer->deserialize($request->getContent(), AddTimeSlotRequest::class, 'json');
        $timeSlot = $this->timeSlotService->addPsychologistTimeSlot($psychologist, $addTimeSlotRequest);

        return new JsonResponse($this->serializer->serialize($timeSlot, 'json'), Response::HTTP_CREATED, [], true);
    }
}
