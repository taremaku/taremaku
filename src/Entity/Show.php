<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
#[ApiResource]
class Show
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank]
    private string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $summary;

    /**
     * @ORM\Column(type="integer")
     */
    #[Assert\NotBlank]
    private int $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $poster;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $website;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $rating;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private ?string $language;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $slug;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $runtime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $premiered;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $idTvmaze;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $idImdb;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $apiUpdate;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $updatedAt;

    /**
     * @var Collection|Season[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Season", mappedBy="tvShow")
     */
    private Collection $seasons;

    /**
     * @var Collection|Following[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Following", mappedBy="tvShow")
     */
    private Collection $followings;

    /**
     * @var Collection|Genre[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Genre", inversedBy="shows")
     */
    private Collection $genres;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Type", inversedBy="shows")
     */
    private ?Type $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Network", inversedBy="shows")
     */
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

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
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

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
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

    public function getIdImdb(): ?int
    {
        return $this->idImdb;
    }

    public function setIdImdb(?int $idImdb): self
    {
        $this->idImdb = $idImdb;
        return $this;
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
        if (!$this->genre->contains($genre)) {
            $this->genre[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genre->contains($genre)) {
            $this->genre->removeElement($genre);
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
