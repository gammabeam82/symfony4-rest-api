<?php

namespace App\RestController;

use App\Entity\Post;
use App\Request\Post\CreatePostRequest;
use App\Request\Post\UpdatePostRequest;
use App\Service\PostService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/v1/posts")
 */
class PostController extends FOSRestController
{
    /**
     * @Rest\Post("/", name="create_post")
     *
     * @param CreatePostRequest $postRequest
     * @param PostService $service
     *
     * @return View
     */
    public function createPostAction(CreatePostRequest $postRequest, PostService $service): View
    {
        $post = $service->create($postRequest);

        return View::create($post, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Put("/{id}", name="update_post")
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
     * @Rest\View(serializerGroups={"details"})
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
     * @Rest\Delete("/{id}", name="delete_post")
     * @Rest\View(serializerGroups={"list"})
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
     * @Rest\Get("/", name="get_all_posts")
     * @Rest\View(serializerGroups={"list"})
     *
     * @param PostService $service
     *
     * @return View
     */
    public function getPostsAction(PostService $service): View
    {
        return View::create($service->getAllPosts(), Response::HTTP_OK);
    }
}
