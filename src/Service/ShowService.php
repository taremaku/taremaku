<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Show;
use Doctrine\Common\Collections\Collection;

class ShowService
{
    public const OPERATION_SEARCH = 'search';
    public const OPERATION_GET_SHOW_DETAILS = 'get-show-details';
    public const OPERATION_GET_SHOW_FULL = 'get-show-full';
    public const OPERATION_SAVE_SHOW = 'save-show';

    public function __construct(
        private CacheService $cacheService
    ) {
    }

    public function searchShow(string $search): ?Collection
    {
        $search = str_replace("+", " ", $search);

        return $this->cacheService->retrieveData(self::OPERATION_SEARCH, $search);
    }

    public function getShowDetails(int $id): ?Show
    {
        return $this->cacheService->retrieveData(self::OPERATION_GET_SHOW_DETAILS, $id);
    }

    public function getShowFull(int $id): ?Show
    {
        return $this->cacheService->retrieveData(self::OPERATION_GET_SHOW_FULL, $id);
    }

    public function saveShow(int $id): Show | bool
    {
        $show = $this->cacheService->retrieveData(self::OPERATION_SAVE_SHOW, $id);

        if ($show) {
            return $show;
        }

        return false;
    }
}
