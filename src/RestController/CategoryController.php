<?php

namespace App\RestController;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Request\Category\CreateCategoryRequest;
use App\Request\Category\UpdateCategoryRequest;
use App\Service\CategoryService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/categories")
 */
class CategoryController extends FOSRestController
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * CategoryController constructor.
     *
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Rest\Post("/", name="create_category")
     * @Rest\View(serializerGroups={"category_details"})
     *
     * @param CreateCategoryRequest $categoryRequest
     *
     * @return View
     */
    public function createCategoryAction(CreateCategoryRequest $categoryRequest): View
    {
        $category = $this->categoryService->create($categoryRequest);

        return View::create($category, Response::HTTP_CREATED);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Rest\Patch("/{id}", name="update_category")
     * @Rest\View(serializerGroups={"category_list"})
     * @ParamConverter("category", class="App:Category")
     *
     * @param UpdateCategoryRequest $categoryRequest
     * @param Category $category
     *
     * @return View
     */
    public function updateCategoryAction(UpdateCategoryRequest $categoryRequest, Category $category): View
    {
        $this->categoryService->update($categoryRequest, $category);

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
     * @IsGranted("ROLE_ADMIN")
     * @Rest\Delete("/{id}", name="delete_category")
     * @Rest\View(serializerGroups={"category_list"})
     * @ParamConverter("category", class="App:Category")
     *
     * @param Category $category
     *
     * @return View
     */
    public function deleteCategoryAction(Category $category): View
    {
        $this->categoryService->delete($category);

        return View::create($category, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/", name="get_categories")
     * @Rest\View(serializerGroups={"category_list", "category_posts"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="10")
     * @Rest\QueryParam(name="order", requirements="(asc|desc)", allowBlank=false, default="asc")
     *
     * @param ParamFetcher $paramFetcher
     * @param CategoryRepository $repo
     *
     * @return View
     */
    public function getCategoriesAction(ParamFetcher $paramFetcher, CategoryRepository $repo): View
    {
        return View::create($repo->findByParams($paramFetcher), Response::HTTP_OK);
    }
}
