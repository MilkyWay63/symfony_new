<?php

namespace App\Controller\Posts;

use App\Entity\Post;
use App\Services\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/posts/{post}', name: 'delete_post', methods: ['DELETE'])]
class DeletePostController extends AbstractController
{
    public function __construct(private readonly PostService $postService)
    {
    }

    public function __invoke(Post $post): JsonResponse
    {
        $this->postService->deletePost($post);

        return new JsonResponse(['message' => 'Post deleted successfully'], 200);
    }
}
