<?php

namespace App\Entity;

use App\Repository\RondaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RondaRepository::class)
 */
class Ronda
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Partida::class, mappedBy="ronda")
     */
    private $llistaPartides;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeroDeRonda;

    /**
     * @ORM\ManyToOne(targetEntity=Torneig::class, inversedBy="llistaRondes")
     */
    private $torneig;


    public function __construct()
    {
        $this->llistaPartides = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Partida[]
     */
    public function getLlistaPartides(): Collection
    {
        return $this->llistaPartides;
    }

    public function addLlistaPartide(Partida $llistaPartide): self
    {
        if (!$this->llistaPartides->contains($llistaPartide)) {
            $this->llistaPartides[] = $llistaPartide;
            $llistaPartide->setRonda($this);
        }

        return $this;
    }

    public function removeLlistaPartide(Partida $llistaPartide): self
    {
        if ($this->llistaPartides->removeElement($llistaPartide)) {
            // set the owning side to null (unless already changed)
            if ($llistaPartide->getRonda() === $this) {
                $llistaPartide->setRonda(null);
            }
        }

        return $this;
    }

    public function getNumeroDeRonda(): ?int
    {
        return $this->numeroDeRonda;
    }

    public function setNumeroDeRonda(int $numeroDeRonda): self
    {
        $this->numeroDeRonda = $numeroDeRonda;

        return $this;
    }

    public function getTorneig(): ?Torneig
    {
        return $this->torneig;
    }

    public function setTorneig(?Torneig $torneig): self
    {
        $this->torneig = $torneig;

        return $this;
    }


}
