<?php

namespace App\Request\Post;

use App\Entity\Category;
use App\Entity\PostImage;
use App\Entity\Tag;
use App\Request\RequestObject;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class UpdatePostRequest extends RequestObject
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
     * @Assert\Length(min=3)
     */
    public $summary;

    /**
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
     * @var Tag[]|Collection
     *
     * @Assert\Valid()
     * */
    public $tags;

    /**
     * @Assert\DateTime()
     */
    public $updatedAt;

    /**
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
