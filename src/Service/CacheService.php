<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Show;
use App\Service\Provider\ProviderService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CacheService
{
    public function __construct(
        private TagAwareCacheInterface $cache,
        private ProviderService $provider,
        private EntityManagerInterface $entityManager,
        private string $providerApi
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function retrieveData(string $tag, mixed $itemId): mixed
    {
        switch ($tag) {
            case ShowService::OPERATION_SEARCH:
                return $this->searchCache($tag, $itemId, 7200);

            case ShowService::OPERATION_GET_SHOW_DETAILS:
                return $this->searchCache($tag, $itemId, 21600);

            case ShowService::OPERATION_SAVE_SHOW:
                return $this->searchCache($tag, $itemId, 3600, true);

            case ShowService::OPERATION_GET_SHOW_FULL:
                return $this->searchCache($tag, $itemId, 3600);
        }

        return null;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function searchCache(string $tag, mixed $itemId, int $expirationDuration, bool $registerOnDb = false): mixed
    {
        return $this->cache->get(
            $tag . '-' . $itemId,
            function (ItemInterface $cacheItem) use ($tag, $itemId, $expirationDuration, $registerOnDb) {
                if (!$cacheItem->isHit()) {
                    $cacheItem->expiresAfter($expirationDuration);
                    $cacheItem->tag($tag . '-' . $itemId);

                    if ($tag === ShowService::OPERATION_SEARCH) {
                        return $this->provider->getProvider()->searchShow($itemId);
                    }

                    switch ($tag) {
                        case ShowService::OPERATION_GET_SHOW_DETAILS:
                            $show = $this->entityManager->getRepository(Show::class)->findOneBy(['id' . $this->providerApi => $itemId]);

                            if ($show) {
                                $fullCast = $this->provider->getProvider()->getCastOnly($show);

                                $show->setCast($fullCast);

                                return $show;
                            }

                            return $this->provider->getProvider()->getCast($itemId);
                        

                        case ShowService::OPERATION_SAVE_SHOW:
                            $show = $this->provider->getProvider()->getShowFull($itemId);

                            if ($registerOnDb) {
                                $this->entityManager->persist($show);
                                $this->entityManager->flush();
                            }

                            return $show;
                        

                        case ShowService::OPERATION_GET_SHOW_FULL:
                            $show = $this->entityManager->getRepository(Show::class)->findOneBy(['id' . $this->providerApi => $itemId]);

                            if ($show) {
                                return $show;
                            }

                            return $this->provider->getProvider()->getShowFull($itemId);
                        
                    }
                }

                return $cacheItem;
            }
        );
    }
}
