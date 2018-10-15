<?php

namespace App\Service;

use App\Request\RequestObject;
use App\Request\User\CreateUserRequest;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    /**
     * @var string
     */
    private $directory;

    /**
     * Uploader constructor.
     *
     * @param string $directory
     */
    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    /**
     * @param RequestObject $requestObject
     */
    public function upload(RequestObject $requestObject): void
    {

        /**
         * @var CreateUserRequest $requestObject
         * @var UploadedFile $file
         */
        $file = $requestObject->imagefile;

        try {
            $filename = $this->generateName($file);
            $file->move($this->directory, $filename);

            $requestObject->avatar = $filename;
        } catch (FileException $e) {

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
