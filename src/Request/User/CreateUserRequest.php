<?php

namespace App\Request\User;

use App\Request\RequestObject;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserRequest extends RequestObject
{
    /**
     * @var string
     * @Assert\NotBlank(message="fos_user.username.blank")
     * @Assert\Length(
     *     min=2,
     *     max=180,
     *     minMessage="fos_user.username.short",
     *     maxMessage="fos_user.username.long"
     * )
     * @AppAssert\UniqueValue(
     *     entityClass="App\Entity\User",
     *     field="username",
     *     message="fos_user.username.already_used"
     * )
     */
    public $username;

    /**
     * @var string
     * @Assert\NotBlank(message="fos_user.email.blank")
     * @Assert\Length(
     *     min=2,
     *     max=180,
     *     minMessage="fos_user.email.short",
     *     maxMessage="fos_user.email.long"
     * )
     * @Assert\Email(message="fos_user.email.invalid")
     * @AppAssert\UniqueValue(
     *     entityClass="App\Entity\User",
     *     field="email",
     *     message="fos_user.email.already_used"
     * )
     */
    public $email;

    /**
     * @var string
     * @Assert\NotBlank(message="fos_user.password.blank")
     * @Assert\Length(
     *     min=2,
     *     max=4096,
     *     minMessage="fos_user.password.short"
     * )
     */
    public $password;
}
