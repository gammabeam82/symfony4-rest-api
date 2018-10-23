<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class UserSubscriber implements EventSubscriber
{
    /**
     * @var string
     */
    private $directory;

    /**
     * UserSubscriber constructor.
     *
     * @param string $directory
     */
    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            'preRemove'
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (false === $entity instanceof User || null === $entity->getAvatar()) {
            return;
        }

        unlink(join(DIRECTORY_SEPARATOR, [$this->directory, $entity->getAvatar()]));
    }
}
