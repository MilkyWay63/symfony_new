<?php

namespace App\Services;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Request\CreateUpdatePostRequest;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\JsonResponse;

class PostService
{
    public function __construct(private readonly PostRepository $postRepository)
    {
    }

    public function createPost(CreateUpdatePostRequest $postRequest): Post|JsonResponse
    {
        $post = new Post();
        $post->setTitle($postRequest->getTitle());
        $post->setContent($postRequest->getContent());

        $this->postRepository->save($post);

        return $post;
    }

    public function updatePost(CreateUpdatePostRequest $postRequest, Post $post): Post|JsonResponse
    {
        $post->setTitle($postRequest->title);
        $post->setContent($postRequest->content);

        $this->postRepository->save($post);

        return $post;
    }

    public function deletePost(Post $post): void
    {
        $this->postRepository->remove($post);
    }

    public function getAllPosts(): Pagerfanta
    {
        return $this->postRepository->findAllPosts();
    }
}
