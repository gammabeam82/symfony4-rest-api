<?php

namespace App\Service;

use App\Request\RequestObject;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class Uploader
{
    /**
     * @var PropertyAccessorInterface
     */
    private $accessor;

    /**
     * Uploader constructor.
     *
     * @param PropertyAccessorInterface $accessor
     */
    public function __construct(PropertyAccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * @param RequestObject $requestObject
     * @param string $directory
     */
    public function upload(RequestObject $requestObject, string $directory): void
    {
        $uploads = $requestObject->getUploads();

        if (0 === count($uploads)) {
            return;
        }

        foreach ($uploads as $field) {
            $file = $this->accessor->getValue($requestObject, $field);

            if (false === $file instanceof UploadedFile) {
                continue;
            }

            try {
                $filename = $this->generateName($file);
                $file->move($directory, $filename);
                $this->accessor->setValue($requestObject, $field, $filename);
            } catch (FileException $e) {
                throw new BadRequestHttpException();
            }
        }
    }

    /**
     * @param string $file
     * @param string $directory
     */
    public function removeFile(string $file, string $directory): void
    {
        $filename = join(DIRECTORY_SEPARATOR, [$directory, $file]);

        if (false !== file_exists($filename) && false !== is_file($filename)) {
            unlink($filename);
        }
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    private function generateName(UploadedFile $file): string
    {
        return sprintf("%s.%s", md5(uniqid()), $file->guessExtension());
    }
}
