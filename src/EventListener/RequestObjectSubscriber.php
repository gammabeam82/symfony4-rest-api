<?php

namespace App\EventListener;

use App\Event\RequestObjectEvent;
use App\Events;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class RequestObjectSubscriber implements EventSubscriberInterface
{
    /**
     * @var PropertyAccessorInterface
     */
    private $accessor;

    /**
     * RequestObjectSubscriber constructor.
     *
     * @param PropertyAccessorInterface $accessor
     */
    public function __construct(PropertyAccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Events::REQUEST_OBJECT_EVENT => 'onNewRequest',
        ];
    }

    /**
     * @param RequestObjectEvent $event
     *
     * @throws EntityNotFoundException
     * @throws \ReflectionException
     */
    public function onNewRequest(RequestObjectEvent $event): void
    {
        $dto = $event->getRequestObject();

        if (0 === count($dto->getFiles())) {
            return;
        }

        /** @var FileBag $files */
        $files = $event->getRequest()->files;

        foreach ($dto->getFiles() as $file => $config) {
            if (null === $files->get($file)) {
                continue;
            }

            if (null === $config['class']) {
                $this->accessor->setValue($dto, $config['fileProperty'], $files->get($file));
                return;
            }

            $reflectionClass = new \ReflectionClass($config['class']);

            if (false !== $config['collection']) {
                $collection = new ArrayCollection();
                foreach ($files->get($file) as $uploadedFile) {
                    $attachment = $reflectionClass->newInstance();
                    $attachment->setFile($uploadedFile);
                    $collection->add($attachment);
                }
                $attachment = $collection;
            } else {
                $attachment = $reflectionClass->newInstance();
                $attachment->setFile($files->get($file));
            }

            $this->accessor->setValue($dto, $config['fileProperty'], $attachment);
        }
    }
}
