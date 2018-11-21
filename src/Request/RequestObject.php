<?php

namespace App\Request;

abstract class RequestObject implements RequestObjectInterface
{

    public const FILES = [];
    /**
     * @return array
     */
    public function getFiles(): array
    {
        return static::FILES;
    }
}
