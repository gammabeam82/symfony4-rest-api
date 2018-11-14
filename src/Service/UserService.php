<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\User\ChangeAvatarRequest;
use App\Request\User\ChangeEmailRequest;
use App\Request\User\ChangePasswordRequest;
use App\Request\User\CreateUserRequest;
use FOS\UserBundle\Model\UserManagerInterface;
use Vich\UploaderBundle\Handler\UploadHandler;

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
     * @var UploadHandler
     */
    private $uploadHandler;

    /**
     * UserService constructor.
     *
     * @param UserManagerInterface $manager
     * @param UserRepository $repo
     * @param UploadHandler $uploadHandler
     */
    public function __construct(UserManagerInterface $manager, UserRepository $repo, UploadHandler $uploadHandler)
    {
        $this->manager = $manager;
        $this->repo = $repo;
        $this->uploadHandler = $uploadHandler;
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
     * @param ChangeAvatarRequest $dto
     */
    public function changeUserAvatar(User $user, ChangeAvatarRequest $dto): void
    {
        $user->changeAvatar($dto);

        $this->manager->updateUser($user);
    }

    /**
     * @param User $user
     */
    public function deleteUserAvatar(User $user): void
    {
        $this->uploadHandler->remove($user, 'image');

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
     * @param User $user
     */
    public function addAdmin(User $user): void
    {
        $user->promote();

        $this->manager->updateUser($user);
    }

    /**
     * @param User $user
     */
    public function removeAdmin(User $user): void
    {
        $user->demote();

        $this->manager->updateUser($user);
    }
}
