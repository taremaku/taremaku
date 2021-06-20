<?php

declare(strict_types=1);

namespace App\Entity;

use App\Common\Traits\AutoIdentifiableEntityTrait;
use App\Common\Traits\TimestampableEntityTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Episode
{
    use AutoIdentifiableEntityTrait;
    use TimestampableEntityTrait;

    #[ORM\Column]
    #[Groups(['full_show', 'detailed_show'])]
    private string $name;

    #[ORM\Column]
    #[Groups(['full_show', 'detailed_show'])]
    private int $number;

    #[ORM\Column(nullable: true)]
    #[Groups(['full_show', 'detailed_show'])]
    private ?int $runtime;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['full_show', 'detailed_show'])]
    private ?string $summary = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['full_show', 'detailed_show'])]
    private ?DateTime $airstamp = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['full_show', 'detailed_show'])]
    private ?DateTime $airdate = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['full_show', 'detailed_show'])]
    private ?DateTime $airtime = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['full_show', 'detailed_show'])]
    private ?string $image = null;

    /**
     * @var Collection|Following[]
     */
    #[ORM\OneToMany(mappedBy: 'episode', targetEntity: Following::class, fetch: 'EXTRA_LAZY')]
    private Collection $followings;

    #[ORM\ManyToOne(inversedBy: 'episodes')]
    private ?Season $season;

    public function __construct()
    {
        $this->followings = new ArrayCollection();
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

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

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

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getAirstamp(): ?DateTime
    {
        return $this->airstamp;
    }

    public function setAirstamp(?DateTime $airstamp): self
    {
        $this->airstamp = $airstamp;

        return $this;
    }

    public function getAirdate(): ?DateTime
    {
        return $this->airdate;
    }

    public function setAirdate(?DateTime $airdate): void
    {
        $this->airdate = $airdate;
    }

    public function getAirtime(): ?DateTime
    {
        return $this->airtime;
    }

    public function setAirtime(?DateTime $airtime): void
    {
        $this->airtime = $airtime;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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
            $following->setEpisode($this);
        }

        return $this;
    }

    public function removeFollowing(Following $following): self
    {
        if ($this->followings->contains($following)) {
            $this->followings->removeElement($following);
            // set the owning side to null (unless already changed)
            if ($following->getEpisode() === $this) {
                $following->setEpisode(null);
            }
        }

        return $this;
    }

    public function getSeason(): Season
    {
        return $this->season;
    }

    public function setSeason(Season $season): self
    {
        $this->season = $season;

        return $this;
    }
}
