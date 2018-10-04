<?php

namespace App\Request\Post;

use App\Entity\Category;
use App\Request\RequestObject;
use Symfony\Component\Validator\Constraints as Assert;

class UpdatePostRequest extends RequestObject
{
    public const RELATIONS = [
        'category' => Category::class
    ];

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
     * @Assert\Valid()
     */
    public $category;
}
