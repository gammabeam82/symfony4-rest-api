<?php

namespace App\Request\User;

use App\Request\RequestObject;
use Symfony\Component\Validator\Constraints as Assert;

class ChangeAvatarRequest extends RequestObject
{
    public const UPLOADS = ['avatar'];

    /**
     * @Assert\File(
     *     maxSize="1024k",
     *     mimeTypes={"image/jpeg", "image/png"}
     * )
     */
    public $avatar;
}
