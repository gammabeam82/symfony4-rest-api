<?php

namespace App\EventListener;

use App\Event\RequestObjectEvent;
use App\Events;
use Doctrine\Common\Collections\ArrayCollection;
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
            Events::REQUEST_OBJECT_EVENT => 'mapFiles',
        ];
    }

    /**
     * @param RequestObjectEvent $event
     *
     * @throws \ReflectionException
     */
    public function mapFiles(RequestObjectEvent $event): void
    {
        $dto = $event->getRequestObject();

        if (0 === count($dto->getFiles())) {
            return;
        }

        /** @var FileBag $files */
        $files = $event->getRequest()->files;

        foreach ($dto->getFiles() as $filename => $config) {
            if (null === $files->get($filename)) {
                continue;
            }

            if (null === $config['class']) {
                $this->accessor->setValue($dto, $config['fileProperty'], $files->get($filename));
                continue;
            }

            $reflectionClass = new \ReflectionClass($config['class']);

            if (false !== $config['collection']) {
                $collection = new ArrayCollection();
                foreach ($files->get($filename) as $uploadedFile) {
                    $attachment = $reflectionClass->newInstance();
                    $attachment->setFile($uploadedFile);
                    $collection->add($attachment);
                }
                $attachment = $collection;
            } else {
                $attachment = $reflectionClass->newInstance();
                $attachment->setFile($files->get($filename));
            }

            $this->accessor->setValue($dto, $config['fileProperty'], $attachment);
        }
    }
}
