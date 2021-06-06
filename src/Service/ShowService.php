<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\Common\Collections\Collection;

class ShowService
{
    public function __construct(private CacheService $cacheService)
    {
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function searchShow(string $search): ?Collection
    {
        $search = str_replace("+", " ", $search);

        $shows = $this->cacheService->retrieveData('search', $search);

        return $shows;
    }
}
