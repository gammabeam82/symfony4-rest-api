<?php

namespace App\Factory;

use App\Entity\Comment;
use App\Request\Comment\CreateCommentRequest;

class CommentFactory
{
    /**
     * @return Comment
     */
    public static function create(): Comment
    {
        return new Comment();
    }

    /**
     * @param CreateCommentRequest $dto
     *
     * @return Comment
     */
    public static function createFromDTO(CreateCommentRequest $dto): Comment
    {
        $comment = new Comment();

        $comment
            ->setCreatedAt($dto->createdAt)
            ->setUpdatedAt($dto->updatedAt)
            ->setMessage($dto->message);

        return $comment;
    }
}
