<?php

namespace App\Request\Comment;

use App\Request\RequestObject;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCommentRequest extends RequestObject
{
    /**
     * @Assert\DateTime()
     */
    public $createdAt;

    /**
     * @Assert\DateTime()
     */
    public $updatedAt;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     */
    public $message;

    /**
     * CreateCommentRequest constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }
}
