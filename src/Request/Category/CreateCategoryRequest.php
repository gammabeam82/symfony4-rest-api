<?php

namespace App\Request\Category;

use App\Request\RequestObject;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCategoryRequest extends RequestObject
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     * @AppAssert\UniqueValue(entityClass="App\Entity\Category", field="name")
     */
    public $name;
}
