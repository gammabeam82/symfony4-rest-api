<?php

namespace App\Request\Post;

use App\Entity\Category;
use App\Entity\PostImage;
use App\Entity\Tag;
use App\Request\RequestObject;
use Symfony\Component\Validator\Constraints as Assert;

class CreatePostRequest extends RequestObject
{
    public const RELATIONS = [
        'category' => Category::class,
        'tags' => Tag::class
    ];

    public const FILES = [
        'images' => [
            'class' => PostImage::class,
            'fileProperty' => 'images',
            'collection' => true
        ]
    ];

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    public $title;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=1)
     */
    public $summary;

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
     * @Assert\DateTime()
     */
    public $updatedAt;

    /**
     * @Assert\Valid()
     */
    public $category;

    /**
     * @Assert\Valid()
     * */
    public $tags;

    /**
     * @Assert\Valid()
     */
    public $images;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }
}
