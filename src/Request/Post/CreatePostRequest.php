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
     * @var Category
     *
     * @Assert\Valid()
     */
    public $category;

    /**
     * @var Tag[]|Collection
     *
     * @Assert\Valid()
     */
    public $tags;

    /**
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
