<?php

namespace App\Entity;

use App\Request\User\ChangeAvatarRequest;
use App\Request\User\ChangeEmailRequest;
use App\Request\User\ChangePasswordRequest;
use App\Request\User\CreateUserRequest;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @Groups({"user_list", "user_details"})
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Groups({"user_list", "user_details"})
     */
    protected $username;

    /**
     * @Groups({"user_details"})
     */
    protected $email;

    /**
     * @Groups({"user_details"})
     */
    protected $roles;

    /**
     * @Groups({"user_details"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $avatar;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string|null $avatar
     *
     * @return User
     */
    public function setAvatar(string $avatar = null): User
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param CreateUserRequest $dto
     *
     * @return User
     */
    public static function createFromDTO(CreateUserRequest $dto): User
    {
        $user = new User;

        $user
            ->setUsername($dto->username)
            ->setEmail($dto->email)
            ->setPlainPassword($dto->password)
            ->addRole(UserInterface::ROLE_DEFAULT)
            ->setAvatar($dto->avatar)
            ->setEnabled(true);

        return $user;
    }

    /**
     * @param ChangePasswordRequest $dto
     */
    public function changePassword(ChangePasswordRequest $dto): void
    {
        $this->setPlainPassword($dto->password);
    }

    /**
     * @param ChangeEmailRequest $dto
     */
    public function changeEmail(ChangeEmailRequest $dto): void
    {
        $this->setEmail($dto->email);
    }

    /**
     * @param ChangeAvatarRequest $dto
     */
    public function changeAvatar(ChangeAvatarRequest $dto): void
    {
        $this->setAvatar($dto->avatar);
    }
}
