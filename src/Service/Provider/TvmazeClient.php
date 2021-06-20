<?php

declare(strict_types=1);

namespace App\Service\Provider;

use App\Entity\Cast;
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

        $responseData?->image?->original ? $season->setPoster(str_replace('http://', 'https://', $responseData->image->original)) : null;
        $responseData->episodeOrder ? $season->setEpisodeCount($responseData->episodeOrder) : null;
        $responseData->premiereDate ? $season->setPremiereDate(new DateTime($responseData->premiereDate)) : null;
        $responseData->endDate ? $season->setEndDate(new DateTime($responseData->endDate)) : null;

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

        $responseData->runtime ? $episode->setRuntime($responseData->runtime) : null;
        $responseData->summary ? $episode->setSummary($responseData->summary) : null;
        $responseData->airstamp ? $episode->setAirstamp(new DateTime($responseData->airstamp)) : null;
        $responseData->airdate ? $episode->setAirdate(new DateTime($responseData->airdate)) : null;
        $responseData->airtime ? $episode->setAirtime(new DateTime($responseData->airtime)) : null;
        $responseData?->image?->original ? $episode->setImage(str_replace('http://', 'https://', $responseData->image->original)) : null;

        if ($seasons) {
            foreach ($seasons as $season) {
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

            $fullCast = $content?->_embedded->cast ?: null;

            if (!empty($fullCast)) {
                foreach ($fullCast as $person) {
                    $cast = new stdClass();
                    $cast->character = new stdClass();

                    $imagePerson = null;
                    $imageCharacter = null;

                    $imagePerson = $person?->person?->image?->original ? str_replace('http://', 'https://', $person->person->image->original) : null;
                    $imageCharacter = $person?->character?->image?->original ? str_replace('http://', 'https://', $person->character->image->original) : null;

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

        $responseData->summary ? $show->setSummary($responseData->summary) : null;

        $dbType = null;
        if ($responseData->type ?? null) {
            $dbType = $this->em->getRepository(Type::class)->findOneBy(['name' => $responseData->type]);

            if (empty($dbType)) {
                $dbType = new Type();
                $dbType->setName($responseData->type);
            }
        }
        $show->setType($dbType);

        if ($responseData?->genres && count($responseData->genres) > 0) {
            foreach ($responseData->genres as $genre) {
                $dbGenre = $this->em->getRepository(Genre::class)->findOneBy(['name' => $genre]);

                if (empty($dbGenre)) {
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

        $responseData?->image?->original ? $show->setPoster(str_replace('http://', 'https://', $responseData->image->original)) : null;
        $responseData->officialSite ? $show->setWebsite(str_replace('http://', 'https://', $responseData->officialSite)) : null;
        $responseData?->rating?->average ? $show->setRating($responseData->rating->average) : null;
        $responseData->language ? $show->setLanguage($responseData->language) : null;
        $responseData->runtime ? $show->setRuntime($responseData->runtime) : null;
        $responseData->premiered ? $show->setPremiered($responseData->premiered) : null;
        $responseData?->externals?->imdb ? $show->setIdImdb($responseData->externals->imdb) : null;
        $responseData?->externals?->thetvdb ? $show->setIdTheTvDb($responseData->externals->thetvdb) : null;

        $show->setIdTvmaze($responseData->id);

        $dbNetwork = null;
        if ($responseData?->network->name ?? null) {
            $dbNetwork = $this->em->getRepository(Network::class)->findOneBy(['name' => $responseData->network->name]);

            if (empty($dbNetwork)) {
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

            if (empty($dbWebChannel)) {
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
            $content = json_decode($content, false, 512, JSON_THROW_ON_ERROR);

            $showCast = new ArrayCollection();

            $fullCast = $content ?: null;

            if (!empty($fullCast)) {
                foreach ($fullCast as $person) {
                    $cast = new Cast();

                    $imagePerson = null;
                    $imageCharacter = null;

                    $imagePerson = $person?->person?->image?->original ? str_replace('http://', 'https://', $person->person->image->original) : null;
                    $imageCharacter = $person?->character?->image?->original ? str_replace('http://', 'https://', $person->character->image->original) : null;

                    $cast->setName($person->person->name);
                    $cast->setImage($imagePerson);
                    $cast->setCharacterName($person->character->name);
                    $cast->setCharacterImage($imageCharacter);

                    $showCast->add($cast);
                }

                return $showCast;
            }
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

            return $show;
        }

        return null;
    }
}
