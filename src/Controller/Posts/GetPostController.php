<?php

namespace App\Controller\Posts;

use App\Constants\Group;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/posts/{post}', name: 'get_post', methods: ['GET'])]
class GetPostController extends AbstractController
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }
    public function __invoke(Post $post): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize($post, 'json', [Group::VIEW_POST]), Response::HTTP_CREATED, [], true
        );
    }
}
