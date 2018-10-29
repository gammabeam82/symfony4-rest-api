<?php

namespace App\RestController;

use App\Entity\Category;
use App\Request\Category\CreateCategoryRequest;
use App\Request\Category\UpdateCategoryRequest;
use App\Service\CategoryService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/categories")
 */
class CategoryController extends FOSRestController
{
    /**
     * @Rest\Post("/", name="create_category")
     * @Rest\View(serializerGroups={"category_details"})
     *
     * @param CreateCategoryRequest $categoryRequest
     * @param CategoryService $service
     *
     * @return View
     */
    public function createCategoryAction(CreateCategoryRequest $categoryRequest, CategoryService $service): View
    {
        $category = $service->create($categoryRequest);

        return View::create($category, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Patch("/{id}", name="update_category")
     * @Rest\View(serializerGroups={"category_list"})
     * @ParamConverter("category", class="App:Category")
     *
     * @param UpdateCategoryRequest $categoryRequest
     * @param Category $category
     * @param CategoryService $service
     *
     * @return View
     */
    public function updateCategoryAction(UpdateCategoryRequest $categoryRequest, Category $category, CategoryService $service): View
    {
        $service->update($categoryRequest, $category);

        return View::create($category, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/{id}", name="get_single_category")
     * @Rest\View(serializerGroups={"category_details"})
     * @ParamConverter("category", class="App:Category")
     *
     * @param Category $category
     *
     * @return View
     */
    public function getCategoryAction(Category $category): View
    {
        return View::create($category, Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/{id}", name="delete_category")
     * @Rest\View(serializerGroups={"category_list"})
     * @ParamConverter("category", class="App:Category")
     *
     * @param Category $category
     * @param CategoryService $service
     *
     * @return View
     */
    public function deleteCategoryAction(Category $category, CategoryService $service): View
    {
        $service->delete($category);

        return View::create($category, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/", name="get_all_categories")
     * @Rest\View(serializerGroups={"category_list"})
     *
     * @param CategoryService $service
     *
     * @return View
     */
    public function getCategoriesAction(CategoryService $service): View
    {
        return View::create($service->getAllCategories(), Response::HTTP_OK);
    }
}
