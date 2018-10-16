<?php

namespace App\Service;

use App\Entity\User;
use App\Request\RequestObject;
use App\Request\User\ChangeAvatarRequest;
use App\Request\User\CreateUserRequest;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
     * @param User|null $user
     */
    public function upload(RequestObject $requestObject, User $user = null): void
    {
        /**
         * @var CreateUserRequest | ChangeAvatarRequest $requestObject
         * @var UploadedFile $file
         */
        $file = $requestObject->imagefile;

        if (false === $file instanceof UploadedFile) {
            throw new BadRequestHttpException();
        }

        try {
            $filename = $this->generateName($file);
            $file->move($this->directory, $filename);

            if (null !== $user) {
                $this->removeAvatar($user);
            }

            $requestObject->avatar = $filename;
        } catch (FileException $e) {
            throw new BadRequestHttpException();
        }
    }

    /**
     * @param User $user
     */
    public function removeAvatar(User $user): void
    {
        if (null === $user->getAvatar()) {
            return;
        }

        $filename = join(DIRECTORY_SEPARATOR, [$this->directory, $user->getAvatar()]);

        if (false !== file_exists($filename)) {
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
