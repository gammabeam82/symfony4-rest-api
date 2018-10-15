<?php

namespace App\Request;

abstract class RequestObject implements RequestObjectInterface
{
    public const RELATIONS = [];

    public const UPLOADS = [];

    /**
     * @return array
     */
    public function getRelations(): array
    {
        return static::RELATIONS;
    }

    /**
     * @return array
     */
    public function getUploads(): array
    {
        return static::UPLOADS;
    }
}
