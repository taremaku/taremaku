<?php

declare(strict_types=1);

namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;

class EntityChanged
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        $entity->setCreatedAt(new \DateTimeImmutable());
        $entity->setUpdatedAt(new \DateTime());
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        $entity->setUpdatedAt(new \DateTime());
    }
}
