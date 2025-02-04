<?php

namespace App\Controller\TimeSlots;

use App\Entity\TimeSlot;
use App\Request\UpdateTimeSlotRequest;
use App\Services\TimeSlotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/time-slots/{id}', methods: ['PUT'])]
class UpdateTimeSlotController extends AbstractController
{
    public function __construct(private readonly TimeSlotService $timeSlotService, private readonly SerializerInterface $serializer)
    {
    }

    public function __invoke(TimeSlot $timeSlot, Request $request): JsonResponse
    {
        $updateTimeSlotRequest =  $this->serializer->deserialize($request->getContent(), UpdateTimeSlotRequest::class, 'json');
        $timeSlot = $this->timeSlotService->updateTimeSlot($timeSlot, $updateTimeSlotRequest);

        return new JsonResponse($this->serializer->serialize($timeSlot, 'json'), Response::HTTP_OK, [], true);
    }
}
