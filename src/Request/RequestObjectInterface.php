<?php

namespace App\Request;

interface RequestObjectInterface
{
    public function getRelations(): array;
}
