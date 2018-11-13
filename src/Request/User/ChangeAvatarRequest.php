<?php

namespace App\Request\User;

use App\Request\RequestObject;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * @var UploadedFile
     *
     * @Assert\File(
     *     maxSize="1024k",
     *     mimeTypes={"image/jpeg", "image/png"}
     * )
     */
    public $image;

    /**
     * @var \DateTimeInterface
     *
     * @Assert\DateTime()
     */
    public $updatedAt;

    /**
     * ChangeAvatarRequest constructor.
     */
    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
    }
}
