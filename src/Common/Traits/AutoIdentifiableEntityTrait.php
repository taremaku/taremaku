<?php

declare(strict_types=1);

namespace App\Common\Traits;

use Doctrine\ORM\Mapping as ORM;

trait AutoIdentifiableEntityTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
