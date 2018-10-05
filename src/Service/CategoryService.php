<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Request\Category\CreateCategoryRequest;
use App\Request\Category\UpdateCategoryRequest;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var CategoryRepository
     */
    private $repo;

    /**
     * CategoryService constructor.
     *
     * @param EntityManagerInterface $em
     * @param CategoryRepository $repo
     */
    public function __construct(EntityManagerInterface $em, CategoryRepository $repo)
    {
        $this->em = $em;
        $this->repo = $repo;
    }

    /**
     * @param CreateCategoryRequest $dto
     *
     * @return Category
     */
    public function create(CreateCategoryRequest $dto): Category
    {
        $category = Category::createFromDTO($dto);

        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }

    /**
     * @param UpdateCategoryRequest $dto
     * @param Category $category
     */
    public function update(UpdateCategoryRequest $dto, Category $category): void
    {
        $category->updateFromDTO($dto);

        $this->em->persist($category);
        $this->em->flush();
    }

    /**
     * @param Category $category
     */
    public function delete(Category $category): void
    {
        $this->em->remove($category);
        $this->em->flush();
    }

    /**
     * @return Category[]
     */
    public function getAllCategories(): Array
    {
        return $this->repo->findAll();
    }
}
