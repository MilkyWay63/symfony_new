<?php

namespace App\Controller\Psychologists;

use App\Services\PsychologistService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/psychologists', methods: ['GET'])]
class GetAllPsychologistsController
{
    public function __construct(private readonly PsychologistService $psychologistService, private readonly SerializerInterface $serializer)
    {
    }

    public function __invoke(): JsonResponse
    {
        $psychologists = $this->psychologistService->getPsychologists();

        return new JsonResponse($this->serializer->serialize($psychologists, 'json'), JsonResponse::HTTP_OK, [], true);
    }
}



