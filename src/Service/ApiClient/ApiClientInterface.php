<?php

declare(strict_types=1);

namespace App\Service\ApiClient;

use App\Entity\Episode;
use App\Entity\Season;
use App\Entity\Show;
use Doctrine\Common\Collections\Collection;
use stdClass;

interface ApiClientInterface
{
    public function getShow(int $id): ?Show;

    public function populateShow(stdClass $responseData): Show;

    public function getShowFull(int $id): ?Show;

    public function getSeason(int $id): ?Season;

    public function getSeasons(int $showId): ?Collection;

    public function populateSeason(stdClass $responseData): Season;

    public function getEpisode(int $id): ?Episode;

    public function getEpisodes(int $showId): ?Collection;

    public function getEpisodesFromSeason(int $seasonId): ?Collection;

    public function populateEpisode(stdClass $responseData, ?Collection $seasons): Episode;

    public function getCast(int $showId): ?Show;

    public function getCastOnly(Show $show): ?Collection;

    public function searchShow(string $search): ?Collection;
}
