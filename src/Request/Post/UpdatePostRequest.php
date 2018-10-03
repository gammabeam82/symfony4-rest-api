<?php

namespace App\Request\Post;

use App\Request\RequestObject;
use Symfony\Component\Validator\Constraints as Assert;

class UpdatePostRequest extends RequestObject
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
}
