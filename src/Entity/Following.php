<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Following
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private DateTimeImmutable $startDate;

    #[ORM\Column(nullable: true)]
    private ?DateTime $endDate;

    #[ORM\Column]
    #[Assert\NotBlank]
    private int $status;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?DateTime $updatedAt;

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

    public function getId(): int
    {
        return $this->id;
    }

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
