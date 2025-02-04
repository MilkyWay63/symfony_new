<?php

namespace App\Tests\Controller\Psychologists;

use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\Utils\JwtTokenProvider;
use App\DataFixtures\UserFixtures;

class GetAllPsychologistsTest extends WebTestCase
{
    private KernelBrowser $client;
    private string $jwtToken;
    protected mixed $databaseTool;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([UserFixtures::class]);

        // Get JWT token for the test user
        $this->jwtToken = JwtTokenProvider::getJwtToken($this->client, 'testuser@email.com', 'SecurePass123');
    }

    public function testUnauthorizedAccess(): void
    {
        $this->client->request(
            'GET',
            '/api/psychologists',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(401, $response->getStatusCode(), "Unauthorized user should receive a 401 status code.");
    }

    public function testAuthorizedAccess(): void
    {
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $this->jwtToken));
        $this->client->request(
            'GET',
            '/api/psychologists', // Replace with your actual protected endpoint
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->jwtToken
            ]
        );

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode(), "Authorized user should receive a 200 status code.");
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}
