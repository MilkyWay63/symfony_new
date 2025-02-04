<?php

namespace App\Controller\Psychologists;

use App\Request\CreatePsychologistRequest;
use App\Services\PsychologistService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/psychologists', methods: ['POST'])]
class CreatePsychologistController extends AbstractController
{
    public function __construct(private PsychologistService $psychologistService, private SerializerInterface $serializer)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $createPsychologistRequest =  $this->serializer->deserialize($request->getContent(), CreatePsychologistRequest::class, 'json');
        $psychologist = $this->psychologistService->createPsychologist($createPsychologistRequest);

        return new JsonResponse($this->serializer->serialize($psychologist, 'json'), Response::HTTP_CREATED, [], true);
    }
}
