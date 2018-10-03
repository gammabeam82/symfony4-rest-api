<?php

namespace App\Request\Post;

use App\Request\RequestObject;
use Symfony\Component\Validator\Constraints as Assert;

class CreatePostRequest extends RequestObject
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    public $title;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     */
    public $article;

    /**
     * @Assert\DateTime()
     */
    public $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }
}
