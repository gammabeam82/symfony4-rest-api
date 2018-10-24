<?php

namespace App\Security;

final class Actions
{
    public const VIEW = 'view';
    public const CREATE = 'create';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    /**
     * @return array
     */
    public static function getAllActions(): array
    {
        return [
            self::VIEW,
            self::CREATE,
            self::EDIT,
            self::DELETE
        ];
    }
}
