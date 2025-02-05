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

class DeletePostTest extends WebTestCase
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

    public function testDeletePostUnauthorized(): void
    {
        $this->client->request('DELETE', '/api/posts/1');

        $this->assertResponseStatusCodeSame(401);
    }

    public function testDeletePostAuthorized(): void
    {
        $postRepo = $this->entityManager->getRepository(Post::class);
        $post = $postRepo->findOneBy([]);
        $postId = $post->getId();

        $this->client->request('DELETE', "/api/posts/{$postId}", [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->jwtToken,
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        // Verify in database
        $deletedPost = $postRepo->find($postId);
        $this->assertNull($deletedPost, 'The post was not deleted from the database');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}
