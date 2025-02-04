<?php

namespace App\Services;

use App\Entity\Psychologist;
use App\Repository\PsychologistRepository;
use App\Request\CreatePsychologistRequest;
use Pagerfanta\Pagerfanta;

class PsychologistService
{
    public function __construct(private PsychologistRepository $psychologistRepository)
    {
    }

    /**
     * @return Pagerfanta $psychologists
     */
    public function getPsychologists(): Pagerfanta
    {
        return $this->psychologistRepository->findAllPsychologists();

    }


    public function createPsychologist(CreatePsychologistRequest $request): Psychologist
    {
        $psychologist = (new Psychologist())
            ->setEmail($request->getEmail())
            ->setName($request->getName())
        ;

        $this->psychologistRepository->save($psychologist);

        return $psychologist;
    }
}
