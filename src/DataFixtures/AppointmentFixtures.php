<?php

namespace App\DataFixtures;

use App\Entity\Appointment;
use App\Entity\TimeSlot;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AppointmentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $timeSlot = $this->getReference('time_slot_' . $i . '_' . $j, TimeSlot::class);
                if ($faker->boolean(50)) {
                    $appointment = new Appointment();
                    $appointment->setTimeSlot($timeSlot);
                    $appointment->setClientName($faker->name);
                    $appointment->setClientEmail($faker->email);
                    $timeSlot->setBooked(true);
                    $manager->persist($appointment);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TimeSlotFixtures::class,
        ];
    }
}
