<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
use App\Request\Post\CreatePostRequest;
use App\Request\Post\UpdatePostRequest;
use Doctrine\ORM\EntityManagerInterface;

class PostService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * PostService constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param CreatePostRequest $dto
     * @param User $user
     *
     * @return Post
     */
    public function create(CreatePostRequest $dto, User $user): Post
    {
        $post = Post::createFromDTO($dto);

        $post->setUser($user);

        $this->em->persist($post);
        $this->em->flush();

        return $post;
    }

    /**
     * @param UpdatePostRequest $dto
     * @param Post $post
     */
    public function update(UpdatePostRequest $dto, Post $post): void
    {
        $post->updateFromDTO($dto);

        $this->em->persist($post);
        $this->em->flush();
    }

    /**
     * @param Post $post
     */
    public function delete(Post $post): void
    {
        $this->em->remove($post);
        $this->em->flush();
    }
}
