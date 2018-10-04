<?php

namespace App\Request;

abstract class RequestObject implements RequestObjectInterface
{
    public const RELATIONS = [];

    /**
     * @return array
     */
    public function getRelations(): array
    {
        return static::RELATIONS;
    }
}
