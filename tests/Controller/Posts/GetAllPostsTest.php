<?php

namespace App\Tests\Controller\Posts;

use App\DataFixtures\PostFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\Utils\JwtTokenProvider;
use App\DataFixtures\UserFixtures;

class GetAllPostsTest extends WebTestCase
{
    private KernelBrowser $client;
    private string $jwtToken;
    protected mixed $databaseTool;
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([UserFixtures::class, PostFixtures::class]);

        // Get JWT token for the test user
        $this->jwtToken = JwtTokenProvider::getJwtToken($this->client, 'testuser@email.com', 'SecurePass123');
    }

    public function testGetPostsUnauthorized(): void
    {
        $this->client->request(
            'GET',
            '/api/posts',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(401, $response->getStatusCode(), "Unauthorized user should receive a 401 status code.");
    }

    public function testGetPostsAuthorized(): void
    {
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $this->jwtToken));
        $this->client->request(
            'GET',
            '/api/posts', // Replace with your actual protected endpoint
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->jwtToken
            ]
        );

        $response = $this->client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);

        $post = $data['items'][0];
        $pagination = $data['pagination'];

        $this->assertArrayHasKey('title', $post);
        $this->assertArrayHasKey('content', $post);

        $this->assertArrayHasKey('current_page', $pagination);
        $this->assertArrayHasKey('has_previous_page', $pagination);
        $this->assertArrayHasKey('has_next_page', $pagination);
        $this->assertArrayHasKey('per_page', $pagination);
        $this->assertArrayHasKey('total_items', $pagination);
        $this->assertArrayHasKey('total_pages', $pagination);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}
