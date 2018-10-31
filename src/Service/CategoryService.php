<?php

namespace App\Service;

use App\Entity\Category;
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
     * CategoryService constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
}
