<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::CLEAN_CODE);
    $containerConfigurator->import(SetList::PSR_12);
    $containerConfigurator->import(SetList::DOCTRINE_ANNOTATIONS);

    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, array(
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ));

    $parameters->set(Option::CACHE_DIRECTORY, 'var/cache/.ecs_cache');

    $parameters->set(Option::SKIP, [
        __DIR__ . '*/bootstrap.php',
        __DIR__ . '*/Kernel.php',
    ]);
};
