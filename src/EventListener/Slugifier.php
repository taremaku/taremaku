<?php


namespace App\EventListener;

use App\Entity\Show;
use App\Service\SlugService;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class Slugifier
{
    public function __construct(private SlugService $slugService)
    {
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Show) {
            return;
        }

        $slug = $this->slugService->slugify($entity->getName());

        $entity->setSlug($slug);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Show) {
            return;
        }

        $slug = $this->slugService->slugify($entity->getName());

        $entity->setSlug($slug);
    }
}
