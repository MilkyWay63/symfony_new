<?php

namespace App\Controller\Posts;

use App\Constants\Group;
use App\Services\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/posts', name: 'get_all_posts', methods: ['GET'])]
class GetAllPostsController extends AbstractController
{
    public function __construct(
        private readonly PostService $postService,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $posts = $this->postService->getAllPosts();

        return new JsonResponse($this->serializer->serialize(
            $posts, 'json', ['groups' => Group::VIEW_POSTS_LIST]), Response::HTTP_OK, [], true
        );
    }
}
