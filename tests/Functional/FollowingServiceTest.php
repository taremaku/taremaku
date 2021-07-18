<?php

declare(strict_types=1);

it('should add a new show to follow',
    function ($show) {
        $results = $this->getFollowingService()->addFollowing(
            $show['status'],
            $show['showId'],
            $show['seasonNumberEnd'],
            $show['episodeNumberEnd']
        );

        expect($results)->toBeArray();
        expect($results['statusCode'])->toBe(200);
    }
)->with(
    [
        'status' => 0,
        'showId' => 66,
        'seasonNumberEnd' => 12,
        'episodeNumberEnd' => 1
    ],
    [
        'status' => 1,
        'showId' => 43,
        'seasonNumberEnd' => 4,
        'episodeNumberEnd' => 1
    ],
    [
        'status' => 0,
        'showId' => 342,
        'seasonNumberEnd' => 8,
        'episodeNumberEnd' => 2,
        'seasonNumberStart' => 3,
        'episodeNumberStart' => 1
    ]
)->skip();

it('should edit a following resource',
    function ($following) {
        $results = $this->getFollowingService()->editFollowing(
            $following['status'],
            $following['followingId'],
        );

        expect($results)->toBeArray();
        expect($results['statusCode'])->toBe(200);
    }
)->with(
    [
        'status' => 0,
        'followingId' => 2
    ],
    [
        'status' => 1,
        'followingId' => 1
    ]
)->skip();


it('should delete a following resource',
    function ($following) {
        $results = $this->getFollowingService()->deleteFollowing(
            $following['status'],
            $following['followingId']
        );
        expect($results)->toBeArray();
        expect($results['statusCode'])->toBe(200);
    }
)->with(
    [
        'followingId' => 2
    ],
    [
        'followingId' => 1
    ]
)->skip();
