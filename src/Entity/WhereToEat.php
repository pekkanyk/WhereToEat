<?php

namespace App\Entity;

use App\Repository\WhereToEatRepository;
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
     * @ORM\JoinColumn(nullable=false)
     */
    private $grp;

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
}
