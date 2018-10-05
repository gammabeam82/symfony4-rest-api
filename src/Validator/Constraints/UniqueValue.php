<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueValue extends Constraint
{
    /**
     * @var string
     */
    public $message = "This value is already used.";

    /**
     * @var string
     */
    public $entityClass;

    /**
     * @var string
     */
    public $field;

    /**
     * @return array
     */
    public function getRequiredOptions(): array
    {
        return ["entityClass", "field"];
    }
}
