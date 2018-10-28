<?php

namespace App\RestController;

use App\Entity\User;
use App\Request\User\ChangeAvatarRequest;
use App\Request\User\ChangeEmailRequest;
use App\Request\User\ChangePasswordRequest;
use App\Request\User\CreateUserRequest;
use App\Security\Actions;
use App\Service\Uploader;
use App\Service\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/users")
 */
class UserController extends FOSRestController
{
    /**
     * @Rest\Post("/register", name="create_user")
     * @Rest\View(serializerGroups={"user_details"})
     *
     * @param CreateUserRequest $userRequest
     * @param UserService $service
     *
     * @return View
     */
    public function registerAction(CreateUserRequest $userRequest, UserService $service): View
    {
        $user = $service->createUser($userRequest);

        return View::create($user, Response::HTTP_CREATED);
    }

    /**
     * @IsGranted(Actions::EDIT, subject="user")
     * @Rest\Patch("/{id}/change_password", name="change_password", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"user_details"})
     * @ParamConverter("user", class="App:User")
     *
     * @param ChangePasswordRequest $passwordRequest
     * @param User $user
     * @param UserService $service
     *
     * @return View
     */
    public function changePasswordAction(ChangePasswordRequest $passwordRequest, User $user, UserService $service): View
    {
        $service->changeUserPassword($user, $passwordRequest);

        return View::create($user, Response::HTTP_OK);
    }

    /**
     * @IsGranted(Actions::EDIT, subject="user")
     * @Rest\Patch("/{id}/change_email", name="change_email", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"user_details"})
     * @ParamConverter("user", class="App:User")
     *
     * @param ChangeEmailRequest $emailRequest
     * @param User $user
     * @param UserService $service
     *
     * @return View
     */
    public function changeEmailAction(ChangeEmailRequest $emailRequest, User $user, UserService $service): View
    {
        $service->changeUserEmail($user, $emailRequest);

        return View::create($user, Response::HTTP_OK);
    }

    /**
     * @IsGranted(Actions::EDIT, subject="user")
     * @Rest\Patch("/{id}/change_avatar", name="change_avatar", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"user_details"})
     * @ParamConverter("user", class="App:User")
     *
     * @param ChangeAvatarRequest $avatarRequest
     * @param User $user
     * @param UserService $service
     *
     * @return View
     */
    public function changeAvatarAction(ChangeAvatarRequest $avatarRequest, User $user, UserService $service): View
    {
        $service->changeUserAvatar($user, $avatarRequest);

        return View::create($user, Response::HTTP_OK);
    }

    /**
     * @IsGranted(Actions::EDIT, subject="user")
     * @Rest\Delete("/{id}/delete_avatar", name="delete_avatar", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"user_details"})
     * @ParamConverter("user", class="App:User")
     *
     * @param User $user
     * @param Uploader $service
     *
     * @return View
     */
    public function deleteAvatarAction(User $user, Uploader $service): View
    {
        $service->removeAvatar($user);

        return View::create($user, Response::HTTP_OK);
    }

    /**
     * @IsGranted(Actions::DELETE, subject="user")
     * @Rest\Delete("/{id}", name="delete_user", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"user_list"})
     * @ParamConverter("user", class="App:User")
     *
     * @param User $user
     * @param UserService $service
     *
     * @return View
     */
    public function deleteAction(User $user, UserService $service): View
    {
        $service->deleteUser($user);

        return View::create($user, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/{id}", name="get_single_user", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"user_details"})
     * @ParamConverter("user", class="App:User")
     *
     * @param User $user
     *
     * @return View
     */
    public function getUserAction(User $user): View
    {
        return View::create($user, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/", name="get_all_users")
     * @Rest\View(serializerGroups={"user_list"})
     *
     * @param UserService $service
     *
     * @return View
     */
    public function getUsersAction(UserService $service): View
    {
        return View::create($service->getAllUsers(), Response::HTTP_OK);
    }
}