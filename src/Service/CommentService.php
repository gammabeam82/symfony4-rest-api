<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Request\Comment\CreateCommentRequest;
use App\Request\Comment\UpdateCommentRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommentService
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
     * CommentService constructor.
     *
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param CreateCommentRequest $dto
     *
     * @return Comment
     */
    public function create(CreateCommentRequest $dto, Post $post): Comment
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $comment = Comment::createFromDTO($dto);

        $comment
            ->setPost($post)
            ->setUser($user);

        $this->em->persist($comment);
        $this->em->flush();

        return $comment;
    }

    /**
     * @param UpdateCommentRequest $dto
     * @param Comment $comment
     */
    public function update(UpdateCommentRequest $dto, Comment $comment): void
    {
        $comment->updateFromDTO($dto);

        $this->em->persist($comment);
        $this->em->flush();
    }

    /**
     * @param Comment $comment
     */
    public function delete(Comment $comment): void
    {
        $this->em->remove($comment);
        $this->em->flush();
    }
}
