<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $users = [
            ['email' => 'testuser@email.com', 'password' => 'SecurePass123', 'roles' => ['ROLE_USER']],
            ['email' => $faker->unique()->safeEmail(), 'password' => 'randompass1', 'roles' => ['ROLE_USER']],
            ['email' => $faker->unique()->safeEmail(), 'password' => 'randompass2', 'roles' => ['ROLE_USER']],
            ['email' => $faker->unique()->safeEmail(), 'password' => 'randompass3', 'roles' => ['ROLE_USER']],
            ['email' => $faker->unique()->safeEmail(), 'password' => 'randompass4', 'roles' => ['ROLE_ADMIN']],
        ];

        foreach ($users as $userData) {
            $user = new User();
            $hashedPassword = $this->passwordHasher->hashPassword($user, $userData['password']);
            $user->setPassword($hashedPassword);
            $user->setUsername($userData['email']);

            $user->setRoles($userData['roles']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
