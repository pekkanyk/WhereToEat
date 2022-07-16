<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RestaurantRepository::class)
 */
class Restaurant
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
     * @ORM\Column(type="time")
     */
    private $opens;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class)
     */
    private $groups;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $menu_url;

    /**
     * @ORM\OneToMany(targetEntity=WhereToEat::class, mappedBy="winner")
     */
    private $whereToEats;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
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

    public function getOpens(): ?\DateTimeInterface
    {
        return $this->opens;
    }

    public function setOpens(\DateTimeInterface $opens): self
    {
        $this->opens = $opens;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        $this->groups->removeElement($group);

        return $this;
    }

    public function getMenuUrl(): ?string
    {
        return $this->menu_url;
    }

    public function setMenuUrl(?string $menu_url): self
    {
        $this->menu_url = $menu_url;

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
            $whereToEat->setWinner($this);
        }

        return $this;
    }

    public function removeWhereToEat(WhereToEat $whereToEat): self
    {
        if ($this->whereToEats->removeElement($whereToEat)) {
            // set the owning side to null (unless already changed)
            if ($whereToEat->getWinner() === $this) {
                $whereToEat->setWinner(null);
            }
        }

        return $this;
    }
}
