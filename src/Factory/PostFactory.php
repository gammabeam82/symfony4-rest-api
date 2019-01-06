<?php

namespace App\Factory;

use App\Entity\Post;
use App\Request\Post\CreatePostRequest;

class PostFactory
{
    /**
     * @return Post
     */
    public static function create(): Post
    {
        return new Post();
    }

    /**
     * @param CreatePostRequest $dto
     *
     * @return Post
     */
    public static function createFromDTO(CreatePostRequest $dto): Post
    {
        $post = new Post();

        $post
            ->setTitle($dto->title)
            ->setSummary($dto->summary)
            ->setCreatedAt($dto->createdAt)
            ->setUpdatedAt($dto->updatedAt)
            ->setArticle($dto->article)
            ->setCategory($dto->category)
            ->setTags($dto->tags)
            ->setImages($dto->images);

        return $post;
    }
}
