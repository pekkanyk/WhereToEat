<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="`group`")
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="grp")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=WhereToEat::class, mappedBy="grp")
     */
    private $whereToEats;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->whereToEats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setGrp($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getGrp() === $this) {
                $user->setGrp(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WhereToEat>
     */
    public function getWhereToEats(): Collection
    {
        return $this->whereToEats;
    }

    public function addWhereToEat(WhereToEat $whereToEat): self
    {
        if (!$this->whereToEats->contains($whereToEat)) {
            $this->whereToEats[] = $whereToEat;
            $whereToEat->setGrp($this);
        }

        return $this;
    }

    public function removeWhereToEat(WhereToEat $whereToEat): self
    {
        if ($this->whereToEats->removeElement($whereToEat)) {
            // set the owning side to null (unless already changed)
            if ($whereToEat->getGrp() === $this) {
                $whereToEat->setGrp(null);
            }
        }

        return $this;
    }
}
