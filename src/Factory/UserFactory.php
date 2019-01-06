<?php

namespace App\Factory;

use App\Entity\User;
use App\Request\User\CreateUserRequest;
use App\Security\Roles;

class UserFactory
{
    /**
     * @return User
     */
    public static function create(): User
    {
        return new User();
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
            ->addRole(Roles::ROLE_USER)
            ->setImage($dto->image)
            ->setCreatedAt($dto->createdAt)
            ->setUpdatedAt($dto->updatedAt)
            ->setEnabled(true);

        return $user;
    }
}
