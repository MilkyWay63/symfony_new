<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Generate 10 sample posts
        for ($i = 0; $i < 10; $i++) {
            if ($faker->boolean()) {
                $post = new Post();
                $post->setTitle($faker->sentence()); // Generate a title with 6 words
                $post->setContent($faker->paragraph()); // Generate 3 paragraphs of content
                $post->setUpdatedAt();

                $manager->persist($post);
            }
        }

        $manager->flush();
    }
}

