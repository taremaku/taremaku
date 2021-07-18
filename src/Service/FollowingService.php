<?php

declare(strict_types=1);

namespace App\Service;

use Exception;
use DateTimeImmutable;
use App\Entity\Episode;
use App\Entity\Following;
use App\Repository\EpisodeRepository;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class FollowingService
{
    public const STATUS_IN_DEVELOPMENT = 0;
    public const STATUS_RUNNING = 1;
    public const STATUS_ENDED = 2;

    public const TRACKING_WATCHING = 0;
    public const TRACKING_COMPLETED = 1;
    public const TRACKING_SEE_NEXT = 2;
    public const TRACKING_UPCOMING = 3;
    public const TRACKING_STOPPED = 4;

    public function __construct(
        private ShowService $showService,
        private TokenStorageInterface $token,
        private EntityManagerInterface $entityManager,
        private EpisodeRepository $episodeRepository
    ) {
    }

    public function addFollowing(
        int $status,
        int $showId,
        int $seasonNumberEnd,
        int $episodeNumberEnd,
        ?int $seasonNumberStart = 1,
        ?int $episodeNumberStart = 1,
    ): array {
        $user = $this->token->getToken()?->getUser();

        $following = null;

        try {
            $show = $this->showService->saveShow($showId);

            if (is_null($show)) {
                return [
                    'message' => 'error',
                    'statusCode' => 404
                ];
            }

            $wantedEpisodes = $this->episodeRepository->findPreviousEpisodesFromShow($show, $seasonNumberEnd, $episodeNumberEnd, $seasonNumberStart, $episodeNumberStart);

            $followingTracker = $this->entityManager->getRepository(Following::class)->findOneBy(
                [
                    'user' => $user,
                    'tvShow' => $show,
                    'season' => null,
                    'episode' => null,
                ]
            );

            if (is_null($followingTracker)) {
                $followingTracker = new Following();
                $followingTracker->setUser($user);
                $followingTracker->setTvShow($show);
                $followingTracker->setStatus($status);
                $followingTracker->setSeason(null);
                $followingTracker->setEpisode(null);

                $this->entityManager->persist($followingTracker);
            }

            foreach ($wantedEpisodes as $episode) {
                if (
                    $episode->getNumber >= $episodeNumberStart
                    && $episode->getSeason()->getNumber() >= $seasonNumberStart
                    && $episode->getNumber() <= $episodeNumberEnd
                    && $episode->getSeason()->getNumber() <= $seasonNumberEnd
                ) {
                    $following = $this->entityManager->getRepository(Following::class)->findOneBy(
                        [
                            'user' => $user,
                            'tvShow' => $show,
                            'season' => $episode->getSeason(),
                            'episode' => $episode
                        ]
                    );

                    if (is_null($following)) {
                        $following = new Following();

                        $following->setUser($user);
                        $following->setStartDate(new DateTimeImmutable());
                        $following->setStatus($status);
                        $following->setTvShow($show);
                        $following->setSeason($episode->getSeason());
                        $following->setEpisode($episode);

                        $this->entityManager->persist($following);
                    } else {
                        $following->setStatus($status);
                    }
                }
            }

            $this->entityManager->flush();

            return [
                'message' => 'success',
                'statusCode' => 200
            ];
        } catch (AccessDeniedException $e) {
            return [
                'message' => 'error ' . $e->getMessage(),
                'statusCode' => 403
            ];
        } catch (ConnectionException $e) {
            return [
                'message' => 'error ' . $e->getMessage(),
                'statusCode' => 500
            ];
        } catch (InvalidParameterException $e) {
            return [
                'message' => 'error ' . $e->getMessage(),
                'statusCode' => 400
            ];
        } catch (Exception $e) {
            return [
                'message' => 'error: ' . $e->getMessage(),
                'statusCode' => 500
            ];
        }
    }

    public function editFollowing(
        int $status,
        Following $following
    ): array {
        $user = $this->token->getToken()?->getUser();

        if ($user !== $following->getUser()) {
            return [
                'message' => 'error',
                'statusCode' => 403
            ];
        }

        $following->setStatus($status);
        $this->entityManager->flush();

        return [
            'message' => 'success',
            'statusCode' => 200
        ];
    }

    public function deleteFollowing(
        Following $following
    ): array {
        $user = $this->token->getToken()?->getUser();

        if ($user !== $following->getUser()) {
            return [
                'message' => 'error',
                'statusCode' => 403
            ];
        }

        $this->entityManager->remove($following);
        $this->entityManager->flush();

        return [
            'message' => 'success',
            'statusCode' => 200
        ];
    }
}
