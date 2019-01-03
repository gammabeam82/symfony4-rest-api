<?php

namespace App\RestController;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Request\Tag\CreateTagRequest;
use App\Request\Tag\UpdateTagRequest;
use App\Service\TagService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/tags")
 */
class TagController extends FOSRestController
{
    /**
     * @var TagService
     */
    private $tagService;

    /**
     * TagController constructor.
     *
     * @param TagService $tagService
     */
    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Rest\Post("/", name="create_tag")
     * @Rest\View(serializerGroups={"tag_details"})
     *
     * @param CreateTagRequest $tagRequest
     *
     * @return View
     */
    public function createTagAction(CreateTagRequest $tagRequest): View
    {
        $tag = $this->tagService->create($tagRequest);

        return View::create($tag, Response::HTTP_CREATED);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Rest\Patch("/{id}", name="update_tag")
     * @Rest\View(serializerGroups={"tag_list"})
     * @ParamConverter("tag", class="App:Tag")
     *
     * @param UpdateTagRequest $tagRequest
     * @param Tag $tag
     *
     * @return View
     */
    public function updateTagAction(UpdateTagRequest $tagRequest, Tag $tag): View
    {
        $this->tagService->update($tagRequest, $tag);

        return View::create($tag, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/{id}", name="get_single_tag")
     * @Rest\View(serializerGroups={"tag_details"})
     * @ParamConverter("tag", class="App:Tag")
     *
     * @param Tag $tag
     *
     * @return View
     */
    public function getTagAction(Tag $tag): View
    {
        return View::create($tag, Response::HTTP_OK);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Rest\Delete("/{id}", name="delete_tag")
     * @Rest\View(serializerGroups={"tag_list"})
     * @ParamConverter("tag", class="App:Tag")
     *
     * @param Tag $tag
     *
     * @return View
     */
    public function deleteTagAction(Tag $tag): View
    {
        $this->tagService->delete($tag);

        return View::create($tag, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/", name="get_tags")
     * @Rest\View(serializerGroups={"tag_list", "tag_posts"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="10")
     * @Rest\QueryParam(name="order", requirements="(asc|desc)", allowBlank=false, default="asc")
     *
     * @param ParamFetcher $paramFetcher
     * @param TagRepository $repo
     *
     * @return View
     */
    public function getTagsAction(ParamFetcher $paramFetcher, TagRepository $repo): View
    {
        return View::create($repo->findByParams($paramFetcher), Response::HTTP_OK);
    }
}
