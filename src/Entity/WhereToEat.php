<?php

namespace App\Entity;

use App\Repository\WhereToEatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WhereToEatRepository::class)
 */
class WhereToEat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Group::class, inversedBy="whereToEats")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $grp;

    /**
     * @ORM\OneToMany(targetEntity=Vote::class, mappedBy="whereToEat")
     */
    private $votes;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class, inversedBy="whereToEats")
     */
    private $winner;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $draw;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getGrp(): ?Group
    {
        return $this->grp;
    }

    public function setGrp(?Group $grp): self
    {
        $this->grp = $grp;

        return $this;
    }

    /**
     * @return Collection<int, Vote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setWhereToEat($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getWhereToEat() === $this) {
                $vote->setWhereToEat(null);
            }
        }

        return $this;
    }

    public function getWinner(): ?Restaurant
    {
        return $this->winner;
    }

    public function setWinner(?Restaurant $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    public function isDraw(): ?bool
    {
        return $this->draw;
    }

    public function setDraw(bool $draw): self
    {
        $this->draw = $draw;

        return $this;
    }
}
