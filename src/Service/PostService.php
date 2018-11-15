<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
use App\Request\Post\CreatePostRequest;
use App\Request\Post\UpdatePostRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PostService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * PostService constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param CreatePostRequest $dto
     *
     * @return Post
     */
    public function create(CreatePostRequest $dto): Post
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

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
