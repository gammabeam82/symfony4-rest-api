<?php

namespace App\Request\Post;

use App\Entity\Category;
use App\Entity\Tag;
use App\Request\RequestObject;
use Symfony\Component\Validator\Constraints as Assert;

class CreatePostRequest extends RequestObject
{
    public const RELATIONS = [
        'category' => Category::class,
        'tags' => Tag::class
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
     * @Assert\DateTime()
     */
    public $createdAt;

    /**
     * @Assert\Valid()
     */
    public $category;

    /**
     * @Assert\Valid()
     * */
    public $tags;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }
}
