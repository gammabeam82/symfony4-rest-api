<?php

namespace App\RestController;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Request\Post\CreatePostRequest;
use App\Request\Post\UpdatePostRequest;
use App\Security\Actions;
use App\Service\PostService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/api/v1/posts")
 */
class PostController extends FOSRestController
{
    /**
     * @Rest\Post("/", name="create_post")
     * @Rest\View(serializerGroups={"post_details", "category_list", "user_list", "tag_list"})
     *
     * @param CreatePostRequest $postRequest
     * @param PostService $service
     * @param TokenStorageInterface
     *
     * @return View
     */
    public function createPostAction(CreatePostRequest $postRequest, PostService $service, TokenStorageInterface $tokenStorage): View
    {
        $this->denyAccessUnlessGranted(Actions::CREATE, new Post());

        /** @var User $user */
        $user = $tokenStorage
            ->getToken()
            ->getUser();

        $post = $service->create($postRequest, $user);

        return View::create($post, Response::HTTP_CREATED);
    }

    /**
     * @IsGranted(Actions::EDIT, subject="post")
     * @Rest\Patch("/{id}", name="update_post")
     * @Rest\View(serializerGroups={"post_details", "category_list", "user_list"})
     * @ParamConverter("post", class="App:Post")
     *
     * @param UpdatePostRequest $postRequest
     * @param Post $post
     * @param PostService $service
     *
     * @return View
     */
    public function updatePostAction(UpdatePostRequest $postRequest, Post $post, PostService $service): View
    {
        $service->update($postRequest, $post);

        return View::create($post, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/{id}", name="get_single_post")
     * @Rest\View(serializerGroups={"post_details", "category_list", "user_list", "tag_list"})
     * @ParamConverter("post", class="App:Post")
     *
     * @param Post $post
     *
     * @return View
     */
    public function getPostAction(Post $post): View
    {
        return View::create($post, Response::HTTP_OK);
    }

    /**
     * @IsGranted(Actions::DELETE, subject="post")
     * @Rest\Delete("/{id}", name="delete_post")
     * @Rest\View(serializerGroups={"post_list"})
     * @ParamConverter("post", class="App:Post")
     *
     * @param Post $post
     * @param PostService $service
     *
     * @return View
     */
    public function deletePostAction(Post $post, PostService $service): View
    {
        $service->delete($post);

        return View::create($post, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/", name="get_posts")
     * @Rest\View(serializerGroups={"post_list", "category_list", "user_list", "tag_list"})
     * @Rest\QueryParam(name="query", nullable=true, requirements="[\w]{3,}")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="10")
     * @Rest\QueryParam(name="user", requirements="\d+", map=true)
     * @Rest\QueryParam(name="category", requirements="\d+", map=true)
     * @Rest\QueryParam(name="tags", requirements="\d+", map=true)
     * @Rest\QueryParam(name="order", requirements="(asc|desc)", allowBlank=false, default="desc")
     *
     * @param ParamFetcher $paramFetcher
     * @param PostRepository $repo
     *
     * @return View
     */
    public function getPostsAction(ParamFetcher $paramFetcher, PostRepository $repo): View
    {
        return View::create($repo->findByParams($paramFetcher), Response::HTTP_OK);
    }
}
