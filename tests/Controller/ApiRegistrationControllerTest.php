<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApiRegistrationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
    }

    public function testSuccessfulRegistration(): void
    {

        $this->client->request(
            'POST',
            '/api/registration',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'testuser22@email.com',
                'password' => 'SecurePass123'
            ])
        );

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJson($response->getContent());
        $this->assertStringContainsString('Registered successfully!', $response->getContent());

        // Ensure user is stored in the database
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['username' => 'testuser22@email.com']);
        $this->assertNotNull($user);
        $userRepository->remove($user);


        // Ensure password is hashed
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $this->assertTrue($passwordHasher->isPasswordValid($user, 'SecurePass123'));
    }

    public function testRegistrationWithMissingFields(): void
    {
        $this->client->request(
            'POST',
            '/api/registration',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => '',
                'password' => ''
            ])
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode()); // Should return validation errors in body

        $this->assertJson($response->getContent());
        $this->assertStringContainsString('This value should not be blank', $response->getContent());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}

