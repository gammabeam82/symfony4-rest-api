<?php

namespace App\Request;

interface RequestObjectInterface
{
    public function getRelations(): array;
    public function getUploads(): array;
    public function getFiles(): array;
}
