<?php

declare(strict_types=1);

use App\Entity\Show;

it(
    'return search results',
    function ($show) {
        $results = $this->getShowService()->searchShow($show);

        expect($results)->not->toBeEmpty();
    }
)->with(
    [
        'stargate',
        'star wars',
        'ncis'
    ]
);

it(
    'return the details of a specific show',
    function ($id) {
        $results = $this->getShowService()->getShowDetails($id);

        expect($results)
            ->not->toBeEmpty()
            ->toBeInstanceOf(Show::class)
            ->getName()->toBeString()
            ->getIdTvmaze()->toBeInt()
            ->getCast()->toBeIterable();
    }
)->with(
    [
        66,
        666,
        1338
    ]
);

it(
    'return the full details of a show',
    function ($id) {
        $results = $this->getShowService()->getShowFull($id);

        expect($results)
            ->not->toBeEmpty()
            ->toBeInstanceOf(Show::class)
            ->getName()->toBeString()
            ->getIdTvmaze()->toBeInt()
            ->getSeasons()->toBeIterable();
    }
)->with(
    [
        66,
        666,
        1338
    ]
);

it(
    'save a show in the db',
    function ($id) {
        $results = $this->getShowService()->saveShow($id);

        expect($results)->toBeTrue();
    }
)->with(
    [
        66,
        666,
        1338
    ]
);

it(
    'GET a show from the route',
    function ($url) {
        $client = static::createClient();

        $client->request(
            'GET',
            $url
        );

        $response = json_decode($client->getResponse()->getContent(), false, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        expect($response)
            ->not->toBeEmpty()
            ->name->toBeString()
            ->name->not->toBeEmpty()
            ->cast->toBeIterable();
    }
)->with(
    [
        '/api/show/66',
        '/api/show/666',
        '/api/show/1338'
    ]
);

it(
    'GET a full show from the route',
    function ($url) {
        $client = static::createClient();

        $client->request(
            'GET',
            $url
        );

        $response = json_decode($client->getResponse()->getContent(), false, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        expect($response)
            ->not->toBeEmpty()
            ->name->toBeString()
            ->name->not->toBeEmpty();
    }
)->with(
    [
        '/api/show/66/full',
        '/api/show/666/full',
        '/api/show/1338/full'
    ]
);

it(
    'GET search a show from the route',
    function ($url) {
        $client = static::createClient();

        $client->request(
            'GET',
            $url
        );

        $response = json_decode($client->getResponse()->getContent(), false, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();

        expect($response)
            ->not->toBeEmpty()
            ->toBeIterable();
    }
)->with(
    [
        '/api/show/search/stargate',
        '/api/show/search/star wars',
        '/api/show/search/ncis'
    ]
);
