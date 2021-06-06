<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Show;
use Doctrine\Common\Collections\Collection;

class ShowService
{
    public const OPERATION_SEARCH = 'search';
    public const OPERATION_GET_SHOW_DETAILS = 'get-show-details';
    public const OPERATION_SAVE_SHOW = 'save-show';

    public function __construct(private CacheService $cacheService)
    {
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function searchShow(string $search): ?Collection
    {
        $search = str_replace("+", " ", $search);

        $shows = $this->cacheService->retrieveData(self::OPERATION_SEARCH, $search);

        return $shows;
    }

    public function getShowDetails(int $id): ?Show
    {
        $show = $this->cacheService->retrieveData(self::OPERATION_GET_SHOW_DETAILS, $id);

        return $show;
    }

    public function saveShow(int $id): bool
    {
        $show = $this->cacheService->retrieveData(self::OPERATION_SAVE_SHOW, $id);

        if ($show) {
            return true;
        }

        return false;
    }
}
