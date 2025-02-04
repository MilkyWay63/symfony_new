<?php

namespace App\DataFixtures;

use App\Entity\Psychologist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PsychologistFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $psychologist = new Psychologist();
            $psychologist->setName($faker->name);
            $psychologist->setEmail($faker->unique()->safeEmail);
            $manager->persist($psychologist);
            $this->addReference('psychologist_' . $i, $psychologist);
        }

        $manager->flush();
    }
}
