<?php

namespace App\Tests\Controller\Posts;

use App\DataFixtures\PostFixtures;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\Utils\JwtTokenProvider;
use App\DataFixtures\UserFixtures;

class UpdatePostTest extends WebTestCase
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

    public function testUpdatePostUnauthorized(): void
    {
        $this->client->request('PUT', '/api/posts/1', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Updated Title',
            'content' => 'Unauthorized update attempt',
        ]));


        $this->assertResponseStatusCodeSame(401);
    }

    public function testUpdatePostAuthorized(): void
    {
        $postRepo = $this->entityManager->getRepository(Post::class);
        $post = $postRepo->findOneBy([]);
        $postId = $post->getId();

        $this->client->request('PUT', "/api/posts/{$postId}", [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => 'Bearer ' . $this->jwtToken,
        ], json_encode([
            'title' => 'Updated Title',
            'content' => 'Updated Content',
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        // Verify in database
        $updatedPost = $postRepo->find($postId);
        $this->assertEquals('Updated Title', $updatedPost->getTitle());
        $this->assertEquals('Updated Content', $updatedPost->getContent());
    }

    public function testUpdatePostValidationFailure(): void
    {
        // Fetch an existing post
        $postRepo = $this->entityManager->getRepository(Post::class);
        $post = $postRepo->findOneBy([]);
        $postId = $post->getId();

        // Updating with an empty title
        $this->client->request('PUT', "/api/posts/{$postId}", [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => 'Bearer ' . $this->jwtToken,
        ], json_encode([
            'title' => '',
            'content' => 'Updated Content',
        ]));

        $this->assertResponseStatusCodeSame(400);
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('errors', $data);

        $this->assertEquals('Title must be at least 5 characters long.', $data['errors']['title']);

        // Updating with an empty content
        $this->client->request('PUT', "/api/posts/{$postId}", [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => 'Bearer ' . $this->jwtToken,
        ], json_encode([
            'title' => 'Updated Title',
            'content' => '',
        ]));

        $this->assertResponseStatusCodeSame(400);
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('errors', $data);
        $this->assertEquals('Content is required.', $data['errors']['content']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}
