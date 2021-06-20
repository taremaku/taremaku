<?php

declare(strict_types=1);

namespace App\Entity;

use App\Common\Traits\AutoIdentifiableEntityTrait;
use App\Common\Traits\TimestampableEntityTrait;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Genre
{
    use AutoIdentifiableEntityTrait;
    use TimestampableEntityTrait;

    #[ORM\Column(length: 32)]
    #[Assert\NotBlank]
    #[Groups(['full_show', 'detailed_show'])]
    private string $name;

    #[ORM\ManyToMany(targetEntity: Show::class, mappedBy: 'genres')]
    private Collection | array $shows;


    public function __construct()
    {
        $this->shows = new ArrayCollection();
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

    public function getShows(): Collection
    {
        return $this->shows;
    }

    public function addShow(Show $show): self
    {
        if (!$this->shows->contains($show)) {
            $this->shows[] = $show;
            $show->addGenre($this);
        }

        return $this;
    }

    public function removeShow(Show $show): self
    {
        if ($this->shows->contains($show)) {
            $this->shows->removeElement($show);
            $show->removeGenre($this);
        }

        return $this;
    }
}
