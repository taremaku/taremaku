<?php

declare(strict_types=1);

namespace App\Entity;

use App\Common\Traits\AutoIdentifiableEntityTrait;
use App\Common\Traits\TimestampableEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'tvshow')]
class Show
{
    public const STATUS_IN_DEVELOPMENT = 0;
    public const STATUS_RUNNING = 1;
    public const STATUS_ENDED = 2;

    use AutoIdentifiableEntityTrait;
    use TimestampableEntityTrait;

    #[ORM\Column]
    #[Groups(['search_show', 'detailed_show', 'full_show'])]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['search_show', 'detailed_show', 'full_show'])]
    private ?string $summary = null;

    #[ORM\Column(length: 1)]
    #[Groups(['search_show', 'detailed_show', 'full_show'])]
    #[Assert\NotBlank]
    private int $status;

    #[ORM\Column(nullable: true)]
    #[Groups(['search_show', 'detailed_show', 'full_show'])]
    private ?string $poster = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['search_show', 'detailed_show', 'full_show'])]
    private ?string $website = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['search_show', 'detailed_show', 'full_show'])]
    private ?float $rating = null;

    #[ORM\Column(length: 16)]
    #[Groups(['search_show', 'detailed_show', 'full_show'])]
    private ?string $language = null;

    #[ORM\Column(nullable: true)]
    private string $slug = '';

    #[ORM\Column(nullable: true)]
    #[Groups(['search_show', 'detailed_show', 'full_show'])]
    private ?int $runtime = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['search_show', 'detailed_show', 'full_show'])]
    private ?string $premiered = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['search_show', 'detailed_show', 'full_show'])]
    private ?int $idTvmaze = null;

    #[ORM\Column(length: 12)]
    private ?string $idImdb = null;

    #[ORM\Column(nullable: true)]
    private ?int $idTheTvDb = null;

    #[ORM\Column(nullable: true)]
    private ?int $apiUpdate = null;

    #[ORM\OneToMany(mappedBy: 'tvShow', targetEntity: Season::class, cascade: ['persist'], fetch: 'EAGER')]
    #[Groups(['full_show'])]
    private Collection | array $seasons;

    #[ORM\OneToMany(mappedBy: 'tvShow', targetEntity: Following::class, fetch: 'EAGER')]
    private Collection | array $followings;

    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'shows', cascade: ['persist'])]
    #[Groups(['full_show'])]
    private Collection | array $genres;

    #[ORM\ManyToOne(cascade: ['persist'], fetch: 'EAGER', inversedBy: 'shows')]
    #[Groups(['search_show', 'detailed_show', 'full_show'])]
    private ?Type $type = null;

    #[ORM\ManyToOne(cascade: ['persist'], fetch: 'EAGER', inversedBy: 'shows')]
    #[Groups(['search_show', 'detailed_show', 'full_show'])]
    private ?Network $network = null;

    #[ORM\ManyToOne(cascade: ['persist'], fetch: 'EAGER', inversedBy: 'shows')]
    #[Groups(['search_show', 'detailed_show', 'full_show'])]
    private ?WebChannel $webChannel = null;

    #[Groups(['detailed_show'])]
    private ?Collection $cast;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
        $this->followings = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->cast = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;
        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;
        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;
        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getRuntime(): ?int
    {
        return $this->runtime;
    }

    public function setRuntime(?int $runtime): self
    {
        $this->runtime = $runtime;
        return $this;
    }

    public function getPremiered(): ?string
    {
        return $this->premiered;
    }

    public function setPremiered(?string $premiered): self
    {
        $this->premiered = $premiered;
        return $this;
    }

    public function getIdTvmaze(): ?int
    {
        return $this->idTvmaze;
    }

    public function setIdTvmaze(?int $idTvmaze): self
    {
        $this->idTvmaze = $idTvmaze;
        return $this;
    }

    public function getIdImdb(): ?string
    {
        return $this->idImdb;
    }

    public function setIdImdb(?string $idImdb): self
    {
        $this->idImdb = $idImdb;
        return $this;
    }

    public function getIdTheTvDb(): ?int
    {
        return $this->idTheTvDb;
    }

    public function setIdTheTvDb(?int $idTheTvDb): void
    {
        $this->idTheTvDb = $idTheTvDb;
    }

    public function getApiUpdate(): ?int
    {
        return $this->apiUpdate;
    }

    public function setApiUpdate(?int $apiUpdate): self
    {
        $this->apiUpdate = $apiUpdate;
        return $this;
    }

    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons[] = $season;
            $season->setTvShow($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->contains($season)) {
            $this->seasons->removeElement($season);
            // set the owning side to null (unless already changed)
            if ($season->getTvShow() === $this) {
                $season->setTvShow(null);
            }
        }

        return $this;
    }

    public function getFollowings(): Collection
    {
        return $this->followings;
    }

    public function addFollowing(Following $following): self
    {
        if (!$this->followings->contains($following)) {
            $this->followings[] = $following;
            $following->setTvShow($this);
        }

        return $this;
    }

    public function removeFollowing(Following $following): self
    {
        if ($this->followings->contains($following)) {
            $this->followings->removeElement($following);
            // set the owning side to null (unless already changed)
            if ($following->getTvShow() === $this) {
                $following->setTvShow(null);
            }
        }

        return $this;
    }

    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->contains($genre)) {
            $this->genres->removeElement($genre);
        }

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getNetwork(): ?Network
    {
        return $this->network;
    }

    public function setNetwork(?Network $network): self
    {
        $this->network = $network;
        return $this;
    }

    public function getWebChannel(): ?WebChannel
    {
        return $this->webChannel;
    }

    public function setWebChannel(?WebChannel $webChannel): self
    {
        $this->webChannel = $webChannel;

        return $this;
    }

    public function getCast(): ?Collection
    {
        return $this->cast;
    }

    public function setCast(?Collection $cast): self
    {
        $this->cast = $cast;

        return $this;
    }

    public function addCast(Cast $cast): self
    {
        if (!$this->cast->contains($cast)) {
            $this->cast->add($cast);
        }

        return $this;
    }

    public function removeCast(Cast $cast): self
    {
        if ($this->cast->contains($cast)) {
            $this->cast->removeElement($cast);
        }

        return $this;
    }
}
