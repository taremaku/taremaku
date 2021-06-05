<?php

declare(strict_types=1);

namespace App\Tests\Functional;

it('return search results', function ($show) {
    $results = $this->getShowService()->searchShow($show);
    expect($results)->not->toBeEmpty();
})->with(
    [
        'stargate',
        'star wars',
        'ncis'
    ]
);
