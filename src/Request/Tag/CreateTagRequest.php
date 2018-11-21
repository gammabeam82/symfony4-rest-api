<?php

namespace App\Request\Tag;

use App\Request\RequestObject;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTagRequest extends RequestObject
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     * @AppAssert\UniqueValue(entityClass="App\Entity\Tag", field="name")
     */
    public $name;
}
