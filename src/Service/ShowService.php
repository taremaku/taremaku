<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Provider\ProviderService;

class ShowService
{
    public function __construct(private ProviderService $providerService)
    {
    }

    public function searchShow(string $search): ?array
    {
        $search = str_replace("+", " ", $search);

        $shows = $this->providerService->getProvider()->searchShow($search);

        return $shows;
    }
}
