<?php

namespace App\Factory;

use App\Entity\Tag;
use App\Request\Tag\CreateTagRequest;

class TagFactory
{
    /**
     * @return Tag
     */
    public static function create(): Tag
    {
        return new Tag();
    }

    /**
     * @param CreateTagRequest $dto
     *
     * @return Tag
     */
    public static function createFromDTO(CreateTagRequest $dto): Tag
    {
        $tag = new Tag();
        $tag->setName($dto->name);

        return $tag;
    }
}
