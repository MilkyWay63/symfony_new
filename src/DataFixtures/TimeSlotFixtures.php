<?php

namespace App\DataFixtures;

use App\Entity\Psychologist;
use App\Entity\TimeSlot;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TimeSlotFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $psychologist = $this->getReference('psychologist_' . $i, Psychologist::class);
            for ($j = 0; $j < 3; $j++) {
                $timeSlot = new TimeSlot();
                $timeSlot->setPsychologist($psychologist);
                $timeSlot->setStartTime($faker->dateTimeBetween('now', '+1 week'));
                $timeSlot->setEndTime($faker->dateTimeBetween('+1 week', '+2 weeks'));
                $timeSlot->setBooked(false);
                $manager->persist($timeSlot);
                $this->addReference('time_slot_' . $i . '_' . $j, $timeSlot);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PsychologistFixtures::class,
        ];
    }
}
