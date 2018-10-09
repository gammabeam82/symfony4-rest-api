<?php

namespace App\Entity;

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

    public function __construct()
    {
        parent::__construct();
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
}
