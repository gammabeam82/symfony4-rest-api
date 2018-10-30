<?php

namespace App\RestController;

use App\Entity\Tag;
use App\Request\Tag\CreateTagRequest;
use App\Request\Tag\UpdateTagRequest;
use App\Service\TagService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
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
     * @IsGranted("ROLE_ADMIN")
     * @Rest\Post("/", name="create_tag")
     * @Rest\View(serializerGroups={"tag_details"})
     *
     * @param CreateTagRequest $tagRequest
     * @param TagService $service
     *
     * @return View
     */
    public function createTagAction(CreateTagRequest $tagRequest, TagService $service): View
    {
        $tag = $service->create($tagRequest);

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
     * @param TagService $service
     *
     * @return View
     */
    public function updateTagAction(UpdateTagRequest $tagRequest, Tag $tag, TagService $service): View
    {
        $service->update($tagRequest, $tag);

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
     * @param TagService $service
     *
     * @return View
     */
    public function deleteTagAction(Tag $tag, TagService $service): View
    {
        $service->delete($tag);

        return View::create($tag, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/", name="get_all_tags")
     * @Rest\View(serializerGroups={"tag_list", "tag_posts"})
     *
     * @param TagService $service
     *
     * @return View
     */
    public function getCategoriesAction(TagService $service): View
    {
        return View::create($service->getAllTags(), Response::HTTP_OK);
    }
}
