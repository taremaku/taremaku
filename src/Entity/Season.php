<?php

declare(strict_types=1);

namespace App\Entity;

use App\Common\Traits\AutoIdentifiableEntityTrait;
use App\Common\Traits\TimestampableEntityTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Season
{
    use AutoIdentifiableEntityTrait;
    use TimestampableEntityTrait;

    #[ORM\Column]
    #[Groups(['full_show', 'detailed_show'])]
    private int $number;

    #[ORM\Column(nullable: true)]
    #[Groups(['full_show', 'detailed_show'])]
    private ?string $poster = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['full_show', 'detailed_show'])]
    private ?int $episodeCount = 0;

    #[ORM\Column(nullable: true)]
    #[Groups(['full_show', 'detailed_show'])]
    private ?DateTime $premiereDate = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['full_show', 'detailed_show'])]
    private ?DateTime $endDate = null;

    #[ORM\OneToMany(mappedBy: 'season', targetEntity: Episode::class, cascade: ['persist'], fetch: 'EAGER')]
    #[Groups(['full_show', 'detailed_show'])]
    private Collection | array $episodes;

    #[ORM\OneToMany(mappedBy: 'season', targetEntity: Following::class, fetch: 'EXTRA_LAZY')]
    private Collection | array $followings;

    #[ORM\ManyToOne(fetch: 'EXTRA_LAZY', inversedBy: 'seasons')]
    private Show $tvShow;

    public function __construct()
    {
        $this->episodes = new ArrayCollection();
        $this->followings = new ArrayCollection();
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;
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

    public function getEpisodeCount(): ?int
    {
        return $this->episodeCount;
    }

    public function setEpisodeCount(?int $episodeCount): self
    {
        $this->episodeCount = $episodeCount;
        return $this;
    }

    public function getPremiereDate(): ?DateTime
    {
        return $this->premiereDate;
    }

    public function setPremiereDate(?DateTime $premiereDate): self
    {
        $this->premiereDate = $premiereDate;
        return $this;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTime $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    public function addEpisode(Episode $episode): self
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes[] = $episode;
            $episode->setSeason($this);
        }

        return $this;
    }

    public function removeEpisode(Episode $episode): self
    {
        if ($this->episodes->contains($episode)) {
            $this->episodes->removeElement($episode);
            // set the owning side to null (unless already changed)
            if ($episode->getSeason() === $this) {
                $episode->setSeason(null);
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
            $following->setSeason($this);
        }

        return $this;
    }

    public function removeFollowing(Following $following): self
    {
        if ($this->followings->contains($following)) {
            $this->followings->removeElement($following);
            // set the owning side to null (unless already changed)
            if ($following->getSeason() === $this) {
                $following->setSeason(null);
            }
        }

        return $this;
    }

    public function getTvShow(): Show
    {
        return $this->tvShow;
    }

    public function setTvShow(Show $tvShow): self
    {
        $this->tvShow = $tvShow;
        return $this;
    }
}
