<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUpdatePostRequest
{
    #[Assert\NotBlank(message: "Title is required.")]
    #[Assert\Length(min: 5, max: 255, minMessage: "Title must be at least {{ limit }} characters long.")]
    public string $title;

    #[Assert\NotBlank(message: "Content is required.")]
    public string $content;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): CreateUpdatePostRequest
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): CreateUpdatePostRequest
    {
        $this->content = $content;

        return $this;
    }
}
