<?php

namespace App\Controller\TimeSlots;

use App\Entity\TimeSlot;
use App\Services\TimeSlotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/time-slots/{id}', methods: ['DELETE'])]
class DeleteTimeSlotController extends AbstractController
{
    public function __construct(private readonly TimeSlotService $timeSlotService)
    {
    }

    public function __invoke(TimeSlot $timeSlot): JsonResponse
    {
        $this->timeSlotService->removeTimeSlot($timeSlot);

        return new JsonResponse(['message' => 'Time slot deleted']);
    }
}
