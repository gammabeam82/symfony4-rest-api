<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class FileNamer implements NamerInterface
{
    /**
     * @param object $object
     * @param PropertyMapping $mapping
     *
     * @return string
     */
    public function name($object, PropertyMapping $mapping): string
    {
        /**
         * @var UploadedFile $file
         */
        $file = $mapping->getFile($object);

        return sprintf("post-%s.%s", uniqid(), $file->guessExtension());
    }
}
