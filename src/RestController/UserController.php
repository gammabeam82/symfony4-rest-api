<?php

namespace App\RestController;

use App\Entity\User;
use App\Request\User\ChangeEmailRequest;
use App\Request\User\ChangePasswordRequest;
use App\Request\User\CreateUserRequest;
use App\Security\Actions;
use App\Service\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/v1/users")
 */
class UserController extends FOSRestController
{
    /**
     * @Rest\Post("/register", name="create_user")
     * @Rest\View(serializerGroups={"user_details"})
     *
     * @SWG\Post(
     *     summary="Create new user",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     parameters={
     *          @SWG\Parameter(
     *              in="body",
     *              name="user",
     *              description="",
     *              required=true,
     *              @SWG\Schema(
     *                  type="object",
     *                  required={"username", "email", "password"},
     *                  properties={
     *                      @SWG\Property(
     *                          type="string",
     *                          property="username",
     *                          minimum=2,
     *                          maximum=180
     *                      ),
     *                      @SWG\Property(
     *                          type="string",
     *                          property="email",
     *                          maximum=180
     *                      ),
     *                      @SWG\Property(
     *                          type="string",
     *                          property="password"
     *                      )
     *                  }
     *
     *              )
     *          )
     *     },
     *     responses={
     *         @SWG\Response(
     *             response=201,
     *             description="Created",
     *         ),
     *         @SWG\Response(
     *             response=400,
     *             description="Bad request",
     *         )
     *     }
     * )
     * @SWG\Tag(name="users")
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
     * @Rest\Put("/{id}/change_password", name="change_password")
     * @Rest\View(serializerGroups={"user_details"})
     * @ParamConverter("user", class="App:User")
     *
     * @Security(name="Bearer")
     * @SWG\Put(
     *     summary="Change user's password",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     parameters={
     *         @SWG\Parameter(
     *             in="path",
     *             name="id",
     *             type="integer",
     *             required=true
     *         ),
     *         @SWG\Parameter(
     *             in="header",
     *             name="Authorization",
     *             type="string",
     *             required=true,
     *             default="Bearer token"
     *         ),
     *         @SWG\Parameter(
     *              in="body",
     *              name="user",
     *              description="",
     *              required=true,
     *              @SWG\Schema(
     *                  type="object",
     *                  required={"password", "repeatedPassword"},
     *                  properties={
     *                      @SWG\Property(
     *                          type="string",
     *                          property="password"
     *                      ),
     *                      @SWG\Property(
     *                          type="string",
     *                          property="repeatedPassword"
     *                      )
     *                  }
     *
     *              )
     *          )
     *     },
     *     responses={
     *         @SWG\Response(
     *             response=200,
     *             description="Ok",
     *         ),
     *         @SWG\Response(
     *             response=400,
     *             description="Bad request",
     *         ),
     *         @SWG\Response(
     *             response=401,
     *             description="Expired JWT Token | JWT Token not found | Invalid JWT Token",
     *         ),
     *         @SWG\Response(
     *             response=403,
     *             description="Forbidden",
     *         )
     *     }
     * )
     * @SWG\Tag(name="users")
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
     * @Rest\Put("/{id}/change_email", name="change_email")
     * @Rest\View(serializerGroups={"user_details"})
     * @ParamConverter("user", class="App:User")
     *
     * @Security(name="Bearer")
     * @SWG\Put(
     *     summary="Change user's email",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     parameters={
     *         @SWG\Parameter(
     *             in="path",
     *             name="id",
     *             type="integer",
     *             required=true
     *         ),
     *         @SWG\Parameter(
     *             in="header",
     *             name="Authorization",
     *             type="string",
     *             required=true,
     *             default="Bearer token"
     *         ),
     *         @SWG\Parameter(
     *              in="body",
     *              name="user",
     *              description="",
     *              required=true,
     *              @SWG\Schema(
     *                  type="object",
     *                  required={"email"},
     *                  properties={
     *                      @SWG\Property(
     *                          type="string",
     *                          property="email"
     *                      )
     *                  }
     *
     *              )
     *          )
     *     },
     *     responses={
     *         @SWG\Response(
     *             response=200,
     *             description="Ok",
     *         ),
     *         @SWG\Response(
     *             response=400,
     *             description="Bad request",
     *         ),
     *         @SWG\Response(
     *             response=401,
     *             description="Expired JWT Token | JWT Token not found | Invalid JWT Token",
     *         ),
     *         @SWG\Response(
     *             response=403,
     *             description="Forbidden",
     *         )
     *     }
     * )
     * @SWG\Tag(name="users")
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
     * @IsGranted(Actions::DELETE, subject="user")
     * @Rest\Delete("/{id}", name="delete_user")
     * @Rest\View(serializerGroups={"user_list"})
     * @ParamConverter("user", class="App:User")
     *
     * @Security(name="Bearer")
     * @SWG\Delete(
     *     summary="Delete user",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     parameters={
     *         @SWG\Parameter(
     *             in="path",
     *             name="id",
     *             type="integer",
     *             required=true
     *         ),
     *         @SWG\Parameter(
     *             in="header",
     *             name="Authorization",
     *             type="string",
     *             required=true,
     *             default="Bearer token"
     *         )
     *     },
     *     responses={
     *         @SWG\Response(
     *             response=200,
     *             description="Ok"
     *          ),
     *          @SWG\Response(
     *              response=401,
     *              description="Expired JWT Token | JWT Token not found | Invalid JWT Token",
     *          ),
     *          @SWG\Response(
     *              response=403,
     *              description="Forbidden",
     *          )
     *     }
     * )
     * @SWG\Tag(name="users")
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
     * @Rest\Get("/{id}", name="get_single_user")
     * @Rest\View(serializerGroups={"user_details"})
     * @ParamConverter("user", class="App:User")
     *
     * @Security(name="Bearer")
     * @SWG\Get(
     *     summary="Return single user",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     parameters={
     *         @SWG\Parameter(
     *             in="path",
     *             name="id",
     *             type="integer",
     *             required=true
     *         ),
     *         @SWG\Parameter(
     *             in="header",
     *             name="Authorization",
     *             type="string",
     *             required=true,
     *             default="Bearer token"
     *         )
     *     },
     *     responses={
     *         @SWG\Response(
     *             response=200,
     *             description="Ok",
     *             @SWG\Schema(
     *                 @SWG\Property(
     *                     type="integer",
     *                     property="id"
     *                 ),
     *                 @SWG\Property(
     *                     type="string",
     *                     property="username"
     *                 ),
     *                 @SWG\Property(
     *                     type="string",
     *                     property="email"
     *                 ),
     *                 @SWG\Property(
     *                     type="array",
     *                     property="roles",
     *                     @SWG\Items(
     *                         type="string"
     *                     )
     *                 )
     *             )
     *         ),
     *         @SWG\Response(
     *             response=401,
     *             description="Expired JWT Token | JWT Token not found | Invalid JWT Token",
     *         )
     *     }
     * )
     * @SWG\Tag(name="users")
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
     * @Security(name="Bearer")
     * @SWG\Get(
     *     summary="Return list of users",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     parameters={
     *         @SWG\Parameter(
     *             in="header",
     *             name="Authorization",
     *             type="string",
     *             required=true,
     *             default="Bearer token"
     *         )
     *     },
     *     responses={
     *         @SWG\Response(
     *             response=200,
     *             description="Ok",
     *             @SWG\Schema(
     *                 @SWG\Property(
     *                     type="integer",
     *                     property="id"
     *                 ),
     *                 @SWG\Property(
     *                     type="string",
     *                     property="username"
     *                 )
     *             )
     *         ),
     *         @SWG\Response(
     *             response=401,
     *             description="Expired JWT Token | JWT Token not found | Invalid JWT Token",
     *         )
     *     }
     * )
     * @SWG\Tag(name="users")
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
