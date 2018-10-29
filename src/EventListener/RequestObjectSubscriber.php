<?php

namespace App\EventListener;

use App\Event\RequestObjectEvent;
use App\Events;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class RequestObjectSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PropertyAccessorInterface
     */
    private $accessor;

    /**
     * RequestObjectSubscriber constructor.
     *
     * @param EntityManagerInterface $em
     * @param PropertyAccessorInterface $accessor
     */
    public function __construct(EntityManagerInterface $em, PropertyAccessorInterface $accessor)
    {
        $this->em = $em;
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
     */
    public function onNewRequest(RequestObjectEvent $event): void
    {
        $dto = $event->getRequestObject();

        if (0 === count($dto->getRelations()) && 0 === count($dto->getUploads())) {
            return;
        }

        foreach ($dto->getRelations() as $field => $class) {
            $repo = $this->em->getRepository($class);
            $value = $this->accessor->getValue($dto, $field);

            if (null === $value) {
                continue;
            }

            if (false !== is_array($value)) {
                $collection = new ArrayCollection();
                foreach ($value as $id) {
                    $entity = $this->findEntity($repo, $id);
                    $collection->add($entity);
                }
                $entity = $collection;
            } else {
                $entity = $this->findEntity($repo, $value);
            }

            $this->accessor->setValue($dto, $field, $entity);
        }

        /** @var FileBag $files */
        $files = $event->getRequest()->files;

        foreach ($dto->getUploads() as $field) {
            if (null !== $files->get($field)) {
                $this->accessor->setValue($dto, $field, $files->get($field));
            }
        }

    }

    /**
     * @param ObjectRepository $repo
     * @param int $id
     *
     * @return object
     * @throws EntityNotFoundException
     */
    private function findEntity(ObjectRepository $repo, int $id): object
    {
        $entity = $repo->find($id);

        if (null === $entity) {
            throw new EntityNotFoundException();
        }

        return $entity;
    }
}
