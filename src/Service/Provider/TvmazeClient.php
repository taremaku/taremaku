<?php

declare(strict_types=1);

namespace App\Service\Provider;

use App\Entity\Episode;
use App\Entity\Genre;
use App\Entity\Network;
use App\Entity\Season;
use App\Entity\Show;
use App\Entity\Type;
use App\Entity\WebChannel;
use App\Service\ApiClient\ApiClientInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use stdClass;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function json_decode;
use function str_replace;

class TvmazeClient extends AbstractProvider implements ApiClientInterface
{
    protected string $providerName = 'TvMaze';

    protected string $providerApiUrl = 'https://api.tvmaze.com';

    public function __construct(
        protected ?string $providerApiKey,
        protected ?string $providerApiSecret,
        protected HttpClientInterface $httpClient,
        protected EntityManagerInterface $em
    ) {
        parent::__construct($httpClient, $em);
    }

    public function getSeasons(int $showId): ?Collection
    {
        $response = $this->doRequest('GET', '/shows/' . $showId . '/seasons');

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = json_decode($content);

            $seasons = new ArrayCollection();

            foreach ($content as $season) {
                $seasons->add($this->populateSeason($season));
            }

            return $seasons;
        }

        return null;
    }

    public function populateSeason(stdClass $responseData): Season
    {
        $season = new Season();

        $season->setNumber($responseData->number);

        if ($responseData?->image->original ?? null) {
            $season->setPoster(str_replace('http://', 'https://', $responseData->image->original));
        }

        if ($responseData->episodeOrder ?? null) {
            $season->setEpisodeCount($responseData->episodeOrder);
        }

        if ($responseData->premiereDate ?? null) {
            $season->setPremiereDate(new DateTime($responseData->premiereDate));
        }

        if ($responseData->endDate ?? null) {
            $season->setEndDate(new DateTime($responseData->endDate));
        }

        return $season;
    }

    public function getSeason(int $id): ?Season
    {
        $response = $this->doRequest('GET', '/seasons/' . $id);

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = json_decode($content);

            $season = $this->populateSeason($content);

            return $season;
        }

        return null;
    }

    public function getEpisodes(int $showId): ?Collection
    {
        $response = $this->doRequest('GET', '/shows/' . $showId . '/episodes');

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = json_decode($content);

            $episodes = new ArrayCollection();

            foreach ($content as $episode) {
                $episodes->add($this->populateEpisode($episode));
            }

            return $episodes;
        }

        return null;
    }

    public function populateEpisode(stdClass $responseData, ?Collection $seasons = null): Episode
    {
        $episode = new Episode();

        $episode->setName($responseData->name);
        $episode->setNumber($responseData->number);

        if ($responseData->runtime ?? null) {
            $episode->setRuntime($responseData->runtime);
        }

        if ($responseData->summary ?? null) {
            $episode->setSummary($responseData->summary);
        }

        if ($responseData->airstamp ?? null) {
            $episode->setAirstamp(new DateTime($responseData->airstamp));
        }

        if ($responseData->airdate ?? null) {
            $episode->setAirdate(new DateTime($responseData->airdate));
        }

        if ($responseData->airtime ?? null) {
            $episode->setAirtime(new DateTime($responseData->airtime));
        }

        if ($responseData?->image->original ?? null) {
            $episode->setImage(str_replace('http://', 'https://', $responseData->image->original));
        }

        if ($seasons) {
            foreach($seasons as $season) {
                if ($season->getNumber() === $responseData->season) {
                    $episode->setSeason($season);
                    $season->addEpisode($episode);

                    break;
                }
            }
        }

        return $episode;
    }

    public function getEpisode(int $id): ?Episode
    {
        $response = $this->doRequest('GET', '/episodes/' . $id);

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = json_decode($content);

            $episode = $this->populateEpisode($content);

            return $episode;
        }

        return null;
    }

    public function getEpisodesFromSeason(int $seasonId): ?Collection
    {
        $response = $this->doRequest('GET', '/seasons/' . $seasonId . '/episodes');

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = json_decode($content);

            $episodes = new ArrayCollection();

            foreach ($content as $episode) {
                $episodes->add($this->populateEpisode($episode));
            }

            return $episodes;
        }

        return null;
    }

    public function getCast(int $showId): ?Show
    {
        $response = $this->doRequest('GET', '/shows/' . $showId . '?embed=cast');

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = json_decode($content);

            $showCast = $this->populateShow($content);

            $fullCast = null;
            if ($content?->_embedded->cast ?? null) {
                $fullCast = $content->_embedded->cast;
            }

            if (!empty($fullCast)) {
                foreach ($fullCast as $person) {
                    $cast = new stdClass();
                    $cast->character = new stdClass();

                    $imagePerson = null;
                    $imageCharacter = null;

                    if ($person?->person?->image->original ?? null) {
                        $imagePerson = str_replace('http://', 'https://', $person->person->image->original);
                    }

                    if ($person?->character?->image->original ?? null) {
                        $imageCharacter = str_replace('http://', 'https://', $person->character->image->original);
                    }

                    $cast->name = $person->person->name;
                    $cast->image = $imagePerson;
                    $cast->character->name = $person->character->name;
                    $cast->character->image = $imageCharacter;

                    $showCast->addCast($cast);
                }
            }

            return $showCast;
        }

        return null;
    }

    public function populateShow(stdClass $responseData): Show
    {
        $show = new Show();
        $show->setName($responseData->name);

        if ($responseData->summary ?? null) {
            $show->setSummary($responseData->summary);
        }

        $dbType = null;
        if ($responseData->type ?? null) {
            $dbType = $this->em->getRepository(Type::class)->findOneBy(['name' => $responseData->type]);

            if (is_null($dbType)) {
                $dbType = new Type();
                $dbType->setName($responseData->type);
            }
        }
        $show->setType($dbType);

        if ($responseData?->genres && count($responseData->genres) > 0) {
            foreach ($responseData->genres as $genre) {
                $dbGenre = $this->em->getRepository(Genre::class)->findOneBy(['name' => $genre]);

                if (is_null($dbGenre)) {
                    $dbGenre = new Genre();
                    $dbGenre->setName($genre);
                }
                $show->addGenre($dbGenre);
            }
        }

        match ($responseData->status) {
            'In Development' => $show->setStatus(Show::STATUS_IN_DEVELOPMENT),
            'Running' => $show->setStatus(Show::STATUS_RUNNING),
            default => $show->setStatus(Show::STATUS_ENDED),
        };

        if ($responseData?->image->original ?? null) {
            $show->setPoster(str_replace('http://', 'https://', $responseData->image->original));
        }

        if ($responseData->officialSite ?? null) {
            $show->setWebsite(str_replace('http://', 'https://', $responseData->officialSite));
        }

        if ($responseData?->rating->average ?? null) {
            $show->setRating($responseData->rating->average);
        }

        if ($responseData->language ?? null) {
            $show->setLanguage($responseData->language);
        }

        if ($responseData->runtime ?? null) {
            $show->setRuntime($responseData->runtime);
        }

        if ($responseData->premiered ?? null) {
            $show->setPremiered($responseData->premiered);
        }

        $show->setIdTvmaze($responseData->id);

        if ($responseData?->externals->imdb ?? null) {
            $show->setIdImdb($responseData->externals->imdb);
        }

        if ($responseData?->externals->thetvdb ?? null) {
            $show->setIdTheTvDb($responseData->externals->thetvdb);
        }

        $dbNetwork = null;
        if ($responseData?->network->name ?? null) {
            $dbNetwork = $this->em->getRepository(Network::class)->findOneBy(['name' => $responseData->network->name]);

            if (is_null($dbNetwork)) {
                $dbNetwork = new Network();
                $dbNetwork->setName($responseData->network->name);
            }
        }
        $show->setNetwork($dbNetwork);

        $dbWebChannel = null;
        if ($responseData?->webChannel->name ?? null) {
            $dbWebChannel = $this->em->getRepository(WebChannel::class)->findOneBy(
                ['name' => $responseData->webChannel->name]
            );

            if (is_null($dbWebChannel)) {
                $dbWebChannel = new WebChannel();
                $dbWebChannel->setName($responseData->webChannel->name);
            }
        }
        $show->setWebChannel($dbWebChannel);

        $show->setApiUpdate($responseData->updated);

        return $show;
    }

    public function searchShow(string $search): ?Collection
    {
        $response = $this->doRequest('GET', '/search/shows?q=' . $search);

        $results = new ArrayCollection();

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = json_decode($content);

            foreach ($content as $result) {
                $results->add($this->getShow($result->show->id));
            }

            return $results;
        }

        return null;
    }

    public function getShow(int $id): ?Show
    {
        $response = $this->doRequest('GET', '/shows/' . $id);

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = json_decode($content);

            $show = $this->populateShow($content);

            return $show;
        }

        return null;
    }

    public function getCastOnly(Show $show): ?Collection
    {
        $response = $this->doRequest('GET', '/shows/' . $show->getIdTvmaze() . '/cast');

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = json_decode($content);

            $showCast = new ArrayCollection();

            $fullCast = null;
            if ($content?->_embedded?->cast) {
                $fullCast = $content->_embedded->cast;
            }

            foreach ($fullCast as $person) {
                $cast = new stdClass();
                $cast->character = new stdClass();

                $imagePerson = null;
                $imageCharacter = null;

                if ($person?->person?->image->original ?? null) {
                    $imagePerson = str_replace('http://', 'https://', $person->person->image->original);
                }

                if ($person?->character?->image->original ?? null) {
                    $imageCharacter = str_replace('http://', 'https://', $person->character->image->original);
                }

                $cast->name = $person->person->name;
                $cast->image = $imagePerson;
                $cast->character->name = $person->character->name;
                $cast->character->image = $imageCharacter;

                $showCast->add($cast);
            }

            return $showCast;
        }

        return null;
    }

    public function getShowFull(int $id): ?Show
    {
        $response = $this->doRequest('GET', '/shows/' . $id . '?embed[]=seasons&embed[]=episodes');

        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            $content = json_decode($content);

            $show = $this->populateShow($content);

            $seasons = new ArrayCollection();
            $episodes = new ArrayCollection();

            if ($content?->_embedded?->seasons && count($content->_embedded->seasons) > 0) {
                foreach ($content->_embedded->seasons as $apiSeason) {
                    $season = $this->populateSeason($apiSeason);
                    $seasons->add($season);
                    $show->addSeason($season);
                }
            }

            if ($content?->_embedded?->episodes && count($content->_embedded->episodes) > 0) {
                foreach ($content->_embedded->episodes as $episode) {
                    $episodes->add($this->populateEpisode($episode, $seasons));
                }
            }

            dd($show);

            return $show;
        }

        return null;
    }
}
