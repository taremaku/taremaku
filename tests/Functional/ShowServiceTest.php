<?php

declare(strict_types=1);

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
