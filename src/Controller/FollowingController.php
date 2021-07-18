<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Show;
use App\Entity\Following;
use App\Service\FollowingService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route(path: '/api/following')]
class FollowingController extends AbstractController
{
    public function __construct(
        private FollowingService $followingService
    ) {
    }

    #[Route(
        path: '/new/{status<\d+>}/{showId<\d+>}/{seasonNumberEnd<\d+>}/{episodeNumberEnd<\d+>}/{seasonNumberStart<\d+>?1}/{episodeNumberStart<\d+>?1}',
        name: 'following_add',
        methods: 'POST'
    )]
    public function postFollowing(
        int $status,
        int $showId,
        int $seasonNumberEnd,
        int $episodeNumberEnd,
        ?int $seasonNumberStart = 1,
        ?int $episodeNumberStart = 1,
    ): Response
    {
        $result = $this->followingService->addFollowing(
            $status,
            $showId,
            $seasonNumberEnd,
            $episodeNumberEnd,
            $seasonNumberStart,
            $episodeNumberStart
        );

        return $this->json($result['message'], $result['statusCode']);
    }

    #[Route(
        path: '/edit/{status<\d+>}/{followingId<\d+>}',
        name: 'following_edit',
        methods: 'PUT'
    )]
    #[ParamConverter(
        [
            'value' => 'followingId',
            'class' => Following::class,
            'options' => [
                'id' => 'followingId',
            ],
        ]
    )]
    public function putFollowing(
        int $status,
        Following $following,
    ): Response
    {
        $result = $this->followingService->editFollowing(
            $status,
            $following
        );

        return $this->json($result['message'], $result['statusCode']);
    }

    #[Route(
        path: '/delete/{followingId<\d+>}',
        name: 'following_delete',
        methods: 'DELETE'
    )]
    #[ParamConverter(
        [
            'value' => 'followingId',
            'class' => Following::class,
            'options' => [
                'id' => 'followingId',
            ],
        ]
    )]
    public function deleteFollowing(
        Following $following,
    ): Response
    {
        $result = $this->followingService->deleteFollowing(
            $following
        );

        return $this->json($result['message'], $result['statusCode']);
    }
