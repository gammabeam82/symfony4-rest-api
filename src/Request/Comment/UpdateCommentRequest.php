<?php

namespace App\Request\Comment;

use App\Request\RequestObject;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateCommentRequest extends RequestObject
{
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
     * UpdateCommentRequest constructor.
     */
    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
    }
}
