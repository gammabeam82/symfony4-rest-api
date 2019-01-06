<?php

namespace App\Factory;

use App\Entity\Category;
use App\Request\Category\CreateCategoryRequest;

class CategoryFactory
{
    /**
     * @return Category
     */
    public static function create(): Category
    {
        return new Category();
    }

    /**
     * @param CreateCategoryRequest $dto
     *
     * @return Category
     */
    public static function createFromDTO(CreateCategoryRequest $dto): Category
    {
        $category = new Category();
        $category->setName($dto->name);

        return $category;
    }
}
