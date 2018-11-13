<?php

namespace App\Request\User;

use App\Request\RequestObject;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangeEmailRequest extends RequestObject
{
    /**
     * @var string
     *
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
     * @var \DateTimeInterface
     *
     * @Assert\DateTime()
     */
    public $updatedAt;

    /**
     * ChangeEmailRequest constructor.
     */
    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
    }
}
