<?php

namespace App\Request;

abstract class RequestObject implements RequestObjectInterface
{
    public const RELATIONS = [];

    public const UPLOADS = [];

    public const FILES = [];

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

    /**
     * @return array
     */
    public function getFiles(): array
    {
        return static::FILES;
    }
}
