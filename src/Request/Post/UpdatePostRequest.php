<?php

namespace App\Request\Post;

use App\Entity\Category;
use App\Entity\PostImage;
use App\Entity\Tag;
use App\Request\RequestObject;
use Symfony\Component\Validator\Constraints as Assert;

class UpdatePostRequest extends RequestObject
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
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    public $title;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     */
    public $summary;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     */
    public $article;

    /**
     * @var Category
     *
     * @Assert\Valid()
     */
    public $category;

    /**
     * @var Tag[]
     *
     * @Assert\Valid()
     * */
    public $tags;

    /**
     * @var \DateTimeInterface
     *
     * @Assert\DateTime()
     */
    public $updatedAt;

    /**
     * @var PostImage[]
     *
     * @Assert\Valid()
     */
    public $images;

    /**
     * UpdatePostRequest constructor.
     */
    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
    }
}
