<?php

declare(strict_types=1);

namespace App\Entity;

use App\Common\Traits\AutoIdentifiableEntityTrait;
use App\Common\Traits\TimestampableEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Role
{
    use AutoIdentifiableEntityTrait;
    use TimestampableEntityTrait;

    #[ORM\Column(length: 32)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(length: 12)]
    #[Assert\NotBlank]
    private string $code;

    #[ORM\OneToMany(mappedBy: 'role', targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    private Collection | array $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getRole() === $this) {
                $user->setRole(null);
            }
        }

        return $this;
    }
}
