<?php

namespace App\RestController;

use App\Entity\Comment;
use App\Entity\Post;
use App\Request\Comment\CreateCommentRequest;
use App\Request\Comment\UpdateCommentRequest;
use App\Security\Actions;
use App\Service\CommentService;
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
class CommentController extends FOSRestController
{
    /**
     * @Rest\Post("/{id}/comments/", name="create_post_comment")
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
     * @Rest\Get("/{id}/comments/{comment_id}", name="get_single_post_comment")
     * @Rest\View(serializerGroups={"comment_details", "user_list"})
     * @ParamConverter("comment", class="App:Comment", options={"id"="comment_id"})
     *
     * @param Comment $comment
     *
     * @return View
     */
    public function getPostCommentAction(Comment $comment): View
    {
        return View::create($comment, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/{id}/comments/", name="get_post_comments")
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
}
