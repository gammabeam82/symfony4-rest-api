<?php

namespace App\Request\Post;

use App\Entity\Category;
use App\Entity\PostImage;
use App\Entity\Tag;
use App\Request\RequestObject;
use Doctrine\Common\Collections\Collection;
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
     * @Assert\Length(min=1)
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
     * @var \DateTimeInterface
     *
     * @Assert\DateTime()
     */
    public $createdAt;

    /**
     * @var \DateTimeInterface
     *
     * @Assert\DateTime()
     */
    public $updatedAt;

    /**
     * @var Category
     *
     * @Assert\Valid()
     */
    public $category;

    /**
     * @var Collection|Tag[]
     *
     * @Assert\Valid()
     * */
    public $tags;

    /**
     * @var Collection|PostImage[]
     *
     * @Assert\Valid()
     */
    public $images;

    /**
     * CreatePostRequest constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }
}
