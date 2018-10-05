<?php

namespace App\Request\Category;

use App\Request\RequestObject;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateCategoryRequest extends RequestObject
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    public $name;
}
