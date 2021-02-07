<?php

namespace App\Entity;

use App\Repository\TorneigRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TorneigRepository::class)
 */
class Torneig
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
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Arbitre::class)
     */
    private $arbitre;

    /**
     * @ORM\OneToMany(targetEntity=Ronda::class, mappedBy="torneig")
     */
    private $llistaRondes;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeroByes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pais;

    /**
     * @ORM\ManyToMany(targetEntity=Jugador::class)
     */
    private $llistaJugadors;

    /**
     * @ORM\Column(type="integer")
     */
    private $numRondes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estat;

    public function __construct()
    {
        $this->llistaRondes = new ArrayCollection();
        $this->llistaJugadors = new ArrayCollection();
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getArbitre(): ?Arbitre
    {
        return $this->arbitre;
    }

    public function setArbitre(?Arbitre $arbitre): self
    {
        $this->arbitre = $arbitre;

        return $this;
    }

    /**
     * @return Collection|Ronda[]
     */
    public function getLlistaRondes(): Collection
    {
        return $this->llistaRondes;
    }

    public function addLlistaRonde(Ronda $llistaRonde): self
    {
        if (!$this->llistaRondes->contains($llistaRonde)) {
            $this->llistaRondes[] = $llistaRonde;
            $llistaRonde->setTorneig($this);
        }

        return $this;
    }

    public function removeLlistaRonde(Ronda $llistaRonde): self
    {
        if ($this->llistaRondes->removeElement($llistaRonde)) {
            // set the owning side to null (unless already changed)
            if ($llistaRonde->getTorneig() === $this) {
                $llistaRonde->setTorneig(null);
            }
        }

        return $this;
    }

    public function getNumeroByes(): ?int
    {
        return $this->numeroByes;
    }

    public function setNumeroByes(int $numeroByes): self
    {
        $this->numeroByes = $numeroByes;

        return $this;
    }

    public function getPais(): ?string
    {
        return $this->pais;
    }

    public function setPais(string $pais): self
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * @return Collection|Jugador[]
     */
    public function getLlistaJugadors(): Collection
    {
        return $this->llistaJugadors;
    }

    public function addLlistaJugador(Jugador $llistaJugador): self
    {
        if (!$this->llistaJugadors->contains($llistaJugador)) {
            $this->llistaJugadors[] = $llistaJugador;
        }

        return $this;
    }

    public function removeLlistaJugador(Jugador $llistaJugador): self
    {
        $this->llistaJugadors->removeElement($llistaJugador);

        return $this;
    }

    public function getNumRondes(): ?int
    {
        return $this->numRondes;
    }

    public function setNumRondes(int $numRondes): self
    {
        $this->numRondes = $numRondes;

        return $this;
    }

    public function getEstat(): ?string
    {
        return $this->estat;
    }

    public function setEstat(string $estat): self
    {
        $this->estat = $estat;

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
