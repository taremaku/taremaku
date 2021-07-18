<?php

declare(strict_types=1);

use App\Service\FollowingService;

trait FollowingServiceHelper
{
    public function getFollowingService(): FollowingService
    {
        self::bootKernel();

        $container = self::$container;

        return $container->get(FollowingService::class);
    }
}
