<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;

class ApiRegistrationController extends AbstractController
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    #[Route('/api/registration', name: 'app_api_registration')]
    public function index(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());
        $username = $decoded->username;
        $plaintextPassword = $decoded->password;

        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $user->setUsername($username);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorConstraint = (array) $error->getConstraint();
                $errorsArray[] = $errorConstraint['message'];
            }
            $errorsString = (string)json_encode($errorsArray);

            return new Response($errorsString);
        } else {
            $em->persist($user);
            $em->flush();

            return $this->json(['message' => 'Registered successfully!']);
        }
    }
}
