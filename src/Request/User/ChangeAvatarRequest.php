<?php

namespace App\Request\User;

use App\Request\RequestObject;
use Symfony\Component\Validator\Constraints as Assert;

class ChangeAvatarRequest extends RequestObject
{
    public const FILES = [
        'avatar' => [
            'class' => null,
            'fileProperty' => 'image',
            'collection' => false
        ]
    ];

    /**
     * @Assert\File(
     *     maxSize="1024k",
     *     mimeTypes={"image/jpeg", "image/png"}
     * )
     */
    public $image;

    /**
     * @Assert\DateTime()
     */
    public $updatedAt;

    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
    }
}
