<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

class Cast
{
    #[Groups(['detailed_show'])]
    private string $name;

    #[Groups(['detailed_show'])]
    private ?string $image = null;

    #[Groups(['detailed_show'])]
    private ?string $characterName = null;

    #[Groups(['detailed_show'])]
    private ?string $characterImage = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getCharacterName(): ?string
    {
        return $this->characterName;
    }

    public function setCharacterName(?string $characterName): self
    {
        $this->characterName = $characterName;

        return $this;
    }

    public function getCharacterImage(): ?string
    {
        return $this->characterImage;
    }

    public function setCharacterImage(?string $characterImage): self
    {
        $this->characterImage = $characterImage;

        return $this;
    }
}
