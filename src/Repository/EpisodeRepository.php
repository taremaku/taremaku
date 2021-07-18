<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Show;
use App\Entity\Episode;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class EpisodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Episode::class);
    }

    public function findPreviousEpisodesFromShow(
        Show $show,
        int $lastEpisodeNumber,
        int $lastSeasonNumber,
        ?int $firstEpisodeNumber = 1,
        ?int $firstSeasonNumber = 1
    ): array {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.season', 'se')
            ->leftJoin('se.tvShow', 's')
            ->andWhere('se.id <= :lastSeasonNumber')
            ->andWhere('e.id <= :lastEpisodeNumber')
            ->andWhere('s.id = :showId')
            ->setParameter('lastSeasonNumber', $lastSeasonNumber)
            ->setParameter('lastEpisodeNumber', $lastEpisodeNumber)
            ->setParameter('showId', $show->getId());

        if ($firstEpisodeNumber > 1 || $firstSeasonNumber > 1) {
            $qb = $qb
                ->andWhere('se.id > :firstSeasonNumber')
                ->andWhere('e.id > :firstEpisodeNumber')
                ->setParameter('firstSeasonNumber', $firstSeasonNumber)
                ->setParameter('firstEpisodeNumber', $firstEpisodeNumber);
        }

        return $qb
            ->getQuery()
            ->getResult();
    }
}
