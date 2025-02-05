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

class CreatePostTest extends WebTestCase
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

    public function testCreatePostUnauthorized(): void
    {
        $this->client->request('POST', '/api/posts', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Unauthorized Post',
            'content' => 'This post should not be created',
        ]));

        $this->assertResponseStatusCodeSame(401);
    }

    public function testCreatePostAuthorized(): void
    {
        $this->client->request('POST', '/api/posts', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => 'Bearer ' . $this->jwtToken,
        ], json_encode([
            'title' => 'New Test Post',
            'content' => 'This is a test content for the post',
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);

        // Verify in database
        $postRepo = $this->entityManager->getRepository(Post::class);
        $post = $postRepo->findOneBy(['title' => 'New Test Post']);
        $this->assertNotNull($post, 'The new post was not found in the database');
        $this->assertEquals('This is a test content for the post', $post->getContent());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}
