<?php

declare(strict_types=1);

namespace App\Service\Provider;

use App\Entity\Episode;
use App\Entity\Season;
use App\Entity\Show;
use App\Entity\Type;
use App\Service\ApiClient\ApiClientInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TvmazeClient extends AbstractProvider implements ApiClientInterface
{
    protected string $providerName = 'TvMaze';

    protected string $providerApiUrl = 'https://api.tvmaze.com';

    protected ?string $providerApiKey = '';

    protected ?string $providerApiSecret = '';

    public function __construct(
        ?string $providerApiKey,
        ?string $providerApiSecret,
        protected HttpClientInterface $httpClient,
        protected EntityManagerInterface $em
    ) {
        $this->providerApiKey = $providerApiKey;
        $this->providerApiSecret = $providerApiSecret;

        parent::__construct($httpClient, $em);
    }

    public function getShow(int $id): ?Show
    {
        $response = $this->doRequest('GET', '/shows/' . $id);

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = \json_decode($content);

            $show = new Show();
            $show->setName($content->name);

            if ($content?->summary) {
                $show->setSummary($content->summary);
            }

            $dbType = null;
            if ($content?->type) {
                $dbType = $this->em->getRepository(Type::class)->findOneBy(['name' => $content->type]);

                if (is_null($dbType)) {
                    $dbType = new Type();
                    $dbType->setName($content->type);
                }
            }
            $show->setType($dbType);

            match ($content->status) {
                'In Development' => $show->setStatus(Show::STATUS_IN_DEVELOPMENT),
                'Running' => $show->setStatus(Show::STATUS_RUNNING),
                default => $show->setStatus(Show::STATUS_ENDED),
            };

            if ($content?->image?->original) {
                $show->setPoster(\str_replace('http://', 'https://', $content->image->original));
            }

            if ($content?->officialSite) {
                $show->setWebsite(\str_replace('http://', 'https://', $content->officialSite));
            }

            if ($content?->rating?->average) {
                $show->setRating($content->rating->average);
            }

            if ($content?->language) {
                $show->setLanguage($content->language);
            }

            if ($content?->runtime) {
                $show->setRuntime($content->runtime);
            }

            if ($content?->premiered) {
                $show->setPremiered($content->premiered);
            }

            $show->setIdTvmaze($content->id);

            if ($content?->externals?->imdb) {
                $show->setIdImdb($content->externals->imdb);
            }

            if ($content?->externals?->thetvdb) {
                $show->setIdTheTvDb($content->externals->thetvdb);
            }

            $show->setApiUpdate($content->updated);

            return $show;
        }

        return null;
    }

    public function getSeason(int $id): ?Season
    {
        $response = $this->doRequest('GET', '/seasons/' . $id);

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = \json_decode($content);

            $season = new Season();

            $season->setNumber($content->number);

            if ($content?->image?->original) {
                $season->setPoster(\str_replace('http://', 'https://', $content->image->original));
            }

            if ($content?->episodeOrder) {
                $season->setEpisodeCount($content->episodeOrder);
            }

            if ($content?->premiereDate) {
                $season->setPremiereDate(new DateTime($content->premiereDate));
            }

            if ($content?->endDate) {
                $season->setEndDate(new DateTime($content->endDate));
            }

            return $season;
        }

        return null;
    }

    public function getSeasons(int $showId): ?Collection
    {
        $response = $this->doRequest('GET', '/shows/' . $showId . '/seasons');

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = \json_decode($content);

            $seasons = new ArrayCollection();

            foreach ($content as $season) {
                $seasons->add($this->getSeason($season->id));
            }

            return $seasons;
        }

        return null;
    }

    public function getEpisode(int $id): ?Episode
    {
        $response = $this->doRequest('GET', '/episodes/' . $id);

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = \json_decode($content);

            $episode = new Episode();

            $episode->setName($content->name);
            $episode->setNumber($content->number);

            if ($content?->runtime) {
                $episode->setRuntime($content->runtime);
            }

            if ($content?->summary) {
                $episode->setSummary($content->summary);
            }

            if ($content?->airstamp) {
                $episode->setAirstamp(new DateTime($content->airstamp));
            }

            if ($content?->airdate) {
                $episode->setAirdate(new DateTime($content->airdate));
            }

            if ($content?->airtime) {
                $episode->setAirtime(new DateTime($content->airtime));
            }

            if ($content?->image?->original) {
                $episode->setImage(\str_replace('http://', 'https://', $content->image->original));
            }

            return $episode;
        }

        return null;
    }

    public function getEpisodes(int $showId): ?Collection
    {
        $response = $this->doRequest('GET', '/shows/' . $showId . '/episodes');

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = \json_decode($content);

            $episodes = new ArrayCollection();

            foreach ($content as $episode) {
                $episodes->add($this->getEpisode($episode->id));
            }
        }

        return null;
    }

    public function getEpisodesFromSeason(int $seasonId): ?Collection
    {
        $response = $this->doRequest('GET', '/seasons/' . $seasonId . '/episodes');

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = \json_decode($content);

            $episodes = new ArrayCollection();

            foreach ($content as $episode) {
                $episodes->add($this->getEpisode($episode->id));
            }
        }

        return null;
    }

    public function getCast(int $showId): ?array
    {
        $response = $this->doRequest('GET', '/shows/' . $showId . '/cast');

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = \json_decode($content);

            $showCast = [];

            foreach ($showCast as $person) {
                $imagePerson = null;
                $imageCharacter = null;

                if ($person?->person?->image?->original) {
                    $imagePerson = \str_replace('http://', 'https://', $person->person->image->original);
                }

                if ($person?->character?->image?->original) {
                    $imageCharacter = \str_replace('http://', 'https://', $person->character->image->original);
                }

                $showCast[] = [
                    $person->name => [
                        'image' => $imagePerson,
                        'character' => [
                            'name' => $person->character->name,
                            'image' => $imageCharacter,
                        ]
                    ]
                ];
            }

            return $showCast;
        }

        return null;
    }

    public function searchShow(string $search): ?array
    {
        $response = $this->doRequest('GET', '/search/shows?q=' . $search);

        $results = [];

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = \json_decode($content);

            foreach ($content as $result) {
                $results[] = [
                    $result->score => [
                        $this->getShow($result->show->id),
                    ]
                ];
            }

            return $results;
        }

        return null;
    }
}
