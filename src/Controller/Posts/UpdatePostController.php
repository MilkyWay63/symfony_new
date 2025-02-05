<?php

namespace App\Controller\Posts;

use App\Constants\Group;
use App\Entity\Post;
use App\Request\CreateUpdatePostRequest;
use App\Services\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/posts/{post}', name: 'update_post', methods: ['PUT'])]
class UpdatePostController extends AbstractController
{
    public function __construct(
        private readonly PostService $postService,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function __invoke(#[MapRequestPayload] CreateUpdatePostRequest $request, Post $post): JsonResponse
    {
        $post = $this->postService->updatePost($request, $post);

        return new JsonResponse(
            $this->serializer->serialize($post, 'json', [Group::VIEW_POST]), Response::HTTP_OK, [], true
        );
    }
}
