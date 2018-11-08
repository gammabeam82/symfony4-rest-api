<?php

namespace App\Request\User;

use App\Request\RequestObject;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordRequest extends RequestObject
{
    /**
     * @var string
     * @Assert\NotBlank(message="fos_user.password.blank")
     * @Assert\Length(
     *     min=2,
     *     max=4096,
     *     minMessage="fos_user.password.short"
     * )
     * @Assert\Expression(
     *     "this.password === this.repeatedPassword",
     *     message="fos_user.password.mismatch"
     * )
     */
    public $password;

    /**
     * @var string
     */
    public $repeatedPassword;

    /**
     * @Assert\DateTime()
     */
    public $updatedAt;

    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
    }
}
