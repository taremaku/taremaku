<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Provider\ProviderService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CacheService
{
    public function __construct(
        private TagAwareCacheInterface $cache,
        private ProviderService $provider
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function retrieveData(string $tag, mixed $itemId): mixed
    {
        switch ($tag) {
            case 'search':
            
                return $this->searchCache($tag, $itemId, 7200, false);
            

            case 'show':
            
                $this->cache->get(
                    (string)$itemId,
                    function (ItemInterface $cacheItem) use ($itemId) {
                        if (!$cacheItem->isHit()) {
                            $cacheItem->expiresAfter(3600);
                            return $this->provider->getProvider()->searchShow($itemId);
                        }

                        return $cacheItem;
                    }
                );

                break;
            
        }

        return null;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function searchCache(string $tag, string $itemId, int $expirationDuration, bool $registerOnDb): mixed
    {
        $searchedItem = $this->cache->get(
            'search-' . $itemId,
            function (ItemInterface $cacheItem) use ($tag, $itemId, $expirationDuration, $registerOnDb) {
                if (!$cacheItem->isHit()) {
                    $cacheItem->expiresAfter($expirationDuration);
                    $cacheItem->tag('search-' . $itemId);

                    if ($tag === 'search') {
                        return $this->provider->getProvider()->searchShow($itemId);
                    }
                }

                return $cacheItem;
            }
        );
        return $searchedItem;
    }
}
