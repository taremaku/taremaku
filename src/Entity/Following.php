<?php

declare(strict_types=1);

namespace App\Entity;

use App\Common\Traits\AutoIdentifiableEntityTrait;
use App\Common\Traits\TimestampableEntityTrait;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Following
{
    use AutoIdentifiableEntityTrait;
    use TimestampableEntityTrait;

    #[ORM\Column]
    private DateTimeImmutable $startDate;

    #[ORM\Column(nullable: true)]
    private ?DateTime $endDate;

    #[ORM\Column]
    #[Assert\NotBlank]
    private int $status;

    #[ORM\ManyToOne(fetch: 'EXTRA_LAZY', inversedBy: 'followings')]
    #[Assert\NotBlank]
    private ?User $user;

    #[ORM\ManyToOne(fetch: 'EXTRA_LAZY', inversedBy: 'followings')]
    #[Assert\NotBlank]
    private ?Episode $episode;

    #[ORM\ManyToOne(fetch: 'EXTRA_LAZY', inversedBy: 'followings')]
    #[Assert\NotBlank]
    private ?Season $season;

    #[ORM\ManyToOne(fetch: 'EXTRA_LAZY', inversedBy: 'followings')]
    #[Assert\NotBlank]
    private ?Show $tvShow;

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;
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

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getEpisode(): Episode
    {
        return $this->episode;
    }

    public function setEpisode(?Episode $episode): self
    {
        $this->episode = $episode;
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
