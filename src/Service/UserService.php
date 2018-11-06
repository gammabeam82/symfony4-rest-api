<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\User\ChangeAvatarRequest;
use App\Request\User\ChangeEmailRequest;
use App\Request\User\ChangePasswordRequest;
use App\Request\User\CreateUserRequest;
use FOS\UserBundle\Model\UserManagerInterface;

class UserService
{
    /**
     * @var UserManagerInterface
     */
    private $manager;

    /**
     * @var UserRepository
     */
    private $repo;

    /**
     * @var Uploader
     */
    private $uploader;

    /**
     * @var string
     */
    private $directory;

    /**
     * UserService constructor.
     *
     * @param UserManagerInterface $manager
     * @param UserRepository $repo
     * @param Uploader $uploader
     * @param string $directory
     */
    public function __construct(UserManagerInterface $manager, UserRepository $repo, Uploader $uploader, string $directory)
    {
        $this->manager = $manager;
        $this->repo = $repo;
        $this->uploader = $uploader;
        $this->directory = $directory;
    }

    /**
     * @param CreateUserRequest $userRequest
     *
     * @return User
     */
    public function createUser(CreateUserRequest $userRequest): User
    {
        $this->uploader->upload($userRequest, $this->directory);

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
     * @param ChangeAvatarRequest $dto
     */
    public function changeUserAvatar(User $user, ChangeAvatarRequest $dto): void
    {
        $this->uploader->upload($dto, $this->directory);
        if (null !== $user->getAvatar()) {
            $this->uploader->removeFile($user->getAvatar(), $this->directory);
        }

        $user->changeAvatar($dto);

        $this->manager->updateUser($user);
    }

    /**
     * @param User $user
     */
    public function deleteUserAvatar(User $user): void
    {
        $this->uploader->removeFile($user->getAvatar(), $this->directory);
    }

    /**
     * @param User $user
     */
    public function deleteUser(User $user): void
    {
        $this->manager->deleteUser($user);
    }
}
