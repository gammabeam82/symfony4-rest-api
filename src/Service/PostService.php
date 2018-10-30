<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Request\Post\CreatePostRequest;
use App\Request\Post\UpdatePostRequest;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcher;

class PostService
{
    public const LIMIT = 10;

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
     * @param ParamFetcher $paramFetcher
     *
     * @return Post[]
     */
    public function getFilteredPosts(ParamFetcher $paramFetcher): array
    {
        $params = [
            'offset' => ($paramFetcher->get('page') - 1) * self::LIMIT,
            'limit' => self::LIMIT,
            'order' => $paramFetcher->get('order')
        ];

        if (false === empty($paramFetcher->get('query'))) {
            $params['query'] = $paramFetcher->get('query');
        }

        if (null !== $paramFetcher->get('user')) {
            $params['user'] = $paramFetcher->get('user');
        }

        if (null !== $paramFetcher->get('category')) {
            $params['category'] = $paramFetcher->get('category');
        }

        if (null !== $paramFetcher->get('tags')) {
            $params['tags'] = $paramFetcher->get('tags');
        }

        return $this->repo->findByParams($params);
    }
}
