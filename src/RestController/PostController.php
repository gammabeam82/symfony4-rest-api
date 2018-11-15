<?php

namespace App\RestController;

use App\Entity\Comment;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Request\Comment\CreateCommentRequest;
use App\Request\Comment\UpdateCommentRequest;
use App\Request\Post\CreatePostRequest;
use App\Request\Post\UpdatePostRequest;
use App\Security\Actions;
use App\Service\CommentService;
use App\Service\PostService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/posts")
 */
class PostController extends FOSRestController
{
    /**
     * @Rest\Post("/", name="create_post")
     * @Rest\View(serializerGroups={"post_details", "category_list", "tag_list", "user_list"})
     *
     * @param CreatePostRequest $postRequest
     * @param PostService $service
     *
     * @return View
     */
    public function createPostAction(CreatePostRequest $postRequest, PostService $service): View
    {
        $this->denyAccessUnlessGranted(Actions::CREATE, new Post());

        $post = $service->create($postRequest);

        return View::create($post, Response::HTTP_CREATED);
    }

    /**
     * @IsGranted(Actions::EDIT, subject="post")
     * @Rest\Patch("/{id}", name="update_post")
     * @Rest\View(serializerGroups={"post_details", "category_list", "tag_list", "user_list"})
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
     * @Rest\Post("/{id}/comments", name="create_post_comment")
     * @Rest\View(serializerGroups={"comment_details", "user_list"})
     * @ParamConverter("post", class="App:Post")
     *
     * @param CreateCommentRequest $commentRequest
     * @param Post $post
     * @param CommentService $service
     *
     * @return View
     */
    public function createPostCommentAction(CreateCommentRequest $commentRequest, Post $post, CommentService $service): View
    {
        $comment = $service->create($commentRequest, $post);

        return View::create($comment, Response::HTTP_OK);
    }

    /**
     * @IsGranted(Actions::EDIT, subject="comment")
     * @Rest\Patch("/{id}/comments/{comment_id}", name="update_post_comment")
     * @Rest\View(serializerGroups={"comment_details", "user_list"})
     * @ParamConverter("comment", class="App:Comment", options={"id"="comment_id"})
     *
     * @param CreateCommentRequest $commentRequest
     * @param Comment $comment
     * @param CommentService $service
     *
     * @return View
     */
    public function updatePostCommentAction(UpdateCommentRequest $commentRequest, Comment $comment, CommentService $service): View
    {
        $service->update($commentRequest, $comment);

        return View::create($comment, Response::HTTP_OK);
    }

    /**
     * @IsGranted(Actions::DELETE, subject="comment")
     * @Rest\Delete("/{id}/comments/{comment_id}", name="delete_post_comment")
     * @Rest\View(serializerGroups={"comment_details", "user_list"})
     * @ParamConverter("comment", class="App:Comment", options={"id"="comment_id"})
     *
     * @param Comment $comment
     * @param CommentService $service
     *
     * @return View
     */
    public function deletePostCommentAction(Comment $comment, CommentService $service): View
    {
        $service->delete($comment);

        return View::create($comment, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/{id}/comments", name="get_post_comments")
     * @Rest\View(serializerGroups={"user_list"})
     * @ParamConverter("post", class="App:Post")
     * @Rest\QueryParam(name="details", default=false)
     *
     * @param ParamFetcher $paramFetcher
     * @param Post $post
     *
     * @return View
     */
    public function getPostCommentsAction(ParamFetcher $paramFetcher, Post $post): View
    {
        $view = View::create($post->getComments(), Response::HTTP_OK);

        $context = $view->getContext();

        if (false !== $paramFetcher->get('details')) {
            $context->addGroup('comment_details');
        } else {
            $context->addGroup('comment_list');
        }

        return $view;
    }

    /**
     * @Rest\Get("/{id}", name="get_single_post")
     * @Rest\View(serializerGroups={"post_details", "category_list", "tag_list", "user_list"})
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
     * @Rest\View(serializerGroups={"post_list", "category_list", "tag_list", "user_list"})
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
