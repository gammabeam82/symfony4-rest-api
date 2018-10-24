<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
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
     * @var PostRepository
     */
    private $repo;

    /**
     * PostService constructor.
     *
     * @param EntityManagerInterface $em
     * @param PostRepository $repo
     */
    public function __construct(EntityManagerInterface $em, PostRepository $repo)
    {
        $this->em = $em;
        $this->repo = $repo;
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

    /**
     * @return Post[]
     */
    public function getAllPosts(): Array
    {
        return $this->repo->findAll();
    }
}
