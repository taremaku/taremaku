<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use App\Service\ShowService;

trait ShowServiceHelper
{
    public function getShowService(): ShowService
    {
        self::bootKernel();

        $container = self::$container;

        return $container->get('show.srv');
    }
}
