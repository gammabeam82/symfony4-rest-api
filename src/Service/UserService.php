<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\User\ChangeEmailRequest;
use App\Request\User\ChangePasswordRequest;
use App\Request\User\CreateUserRequest;
use FOS\UserBundle\Model\UserManagerInterface;

class UserService
{
    /**
     * @var UserRepository
     */
    private $repo;

    /**
     * @var UserManagerInterface
     */
    private $manager;

    /**
     * UserService constructor.
     *
     * @param UserManagerInterface $manager
     * @param UserRepository $repo
     */
    public function __construct(UserManagerInterface $manager, UserRepository $repo)
    {
        $this->manager = $manager;
        $this->repo = $repo;
    }

    /**
     * @param CreateUserRequest $userRequest
     *
     * @return User
     */
    public function createUser(CreateUserRequest $userRequest): User
    {
        $user = User::createFromDTO($userRequest);

        $this->manager->updateUser($user);

        return $user;
    }

    /**
     * @param User $user
     * @param ChangePasswordRequest $dto
     */
    public function changeUserPassword(User $user, ChangePasswordRequest $dto): void
    {
        $user->changePassword($dto);

        $this->manager->updateUser($user);
    }

    /**
     * @param User $user
     * @param ChangeEmailRequest $dto
     */
    public function changeUserEmail(User $user, ChangeEmailRequest $dto): void
    {
        $user->changeEmail($dto);

        $this->manager->updateUser($user);
    }

    /**
     * @param User $user
     */
    public function deleteUser(User $user): void
    {
        $this->manager->deleteUser($user);
    }

    /**
     * @return User[]
     */
    public function getAllUsers(): array
    {
        return $this->repo->findAll();
    }
}
