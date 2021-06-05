<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class Show
{
    public const STATUS_IN_DEVELOPMENT = 0;
    public const STATUS_RUNNING = 1;
    public const STATUS_ENDED = 2;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $summary = null;

    #[ORM\Column(length: 1)]
    #[Assert\NotBlank]
    private int $status;

    #[ORM\Column(nullable: true)]
    private ?string $poster = null;

    #[ORM\Column(nullable: true)]
    private ?string $website = null;

    #[ORM\Column(nullable: true)]
    private ?float $rating = null;

    #[ORM\Column(length: 16)]
    private ?string $language = null;

    #[ORM\Column(nullable: true)]
    private string $slug;

    #[ORM\Column(nullable: true)]
    private ?int $runtime = null;

    #[ORM\Column(nullable: true)]
    private ?string $premiered = null;

    #[ORM\Column(nullable: true)]
    private ?int $idTvmaze = null;

    #[ORM\Column(length: 8)]
    private ?string $idImdb = null;

    #[ORM\Column(nullable: true)]
    private ?int $idTheTvDb = null;

    #[ORM\Column(nullable: true)]
    private ?int $apiUpdate = null;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?DateTime $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'tvShow', targetEntity: Season::class)]
    private Collection | array $seasons;

    #[ORM\OneToMany(mappedBy: 'tvShow', targetEntity: Following::class)]
    private Collection | array $followings;

    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'shows')]
    private Collection | array $genres;

    #[ORM\ManyToOne(inversedBy: 'shows')]
    private ?Type $type;

    #[ORM\ManyToOne(inversedBy: 'shows')]
    private ?Network $network;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
        $this->followings = new ArrayCollection();
        $this->genres = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
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
}
