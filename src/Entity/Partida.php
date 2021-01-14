<?php

namespace App\Entity;

use App\Repository\PartidaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PartidaRepository::class)
 */
class Partida
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Jugador::class)
     */
    private $pecesBlanques;

    /**
     * @ORM\ManyToOne(targetEntity=Jugador::class)
     */
    private $pecesNegres;

    /**
     * @ORM\Column(type="float")
     */
    private $puntsBlanques;

    /**
     * @ORM\Column(type="float")
     */
    private $puntsNegres;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeroTaula;

    /**
     * @ORM\Column(type="boolean")
     */
    private $bye;

    /**
     * @ORM\ManyToOne(targetEntity=Ronda::class, inversedBy="llistaPartides")
     */
    private $ronda;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPecesBlanques(): ?Jugador
    {
        return $this->pecesBlanques;
    }

    public function setPecesBlanques(?Jugador $pecesBlanques): self
    {
        $this->pecesBlanques = $pecesBlanques;

        return $this;
    }

    public function getPecesNegres(): ?Jugador
    {
        return $this->pecesNegres;
    }

    public function setPecesNegres(?Jugador $pecesNegres): self
    {
        $this->pecesNegres = $pecesNegres;

        return $this;
    }

    public function getPuntsBlanques(): ?float
    {
        return $this->puntsBlanques;
    }

    public function setPuntsBlanques(float $puntsBlanques): self
    {
        $this->puntsBlanques = $puntsBlanques;

        return $this;
    }

    public function getPuntsNegres(): ?float
    {
        return $this->puntsNegres;
    }

    public function setPuntsNegres(float $puntsNegres): self
    {
        $this->puntsNegres = $puntsNegres;

        return $this;
    }

    public function getNumeroTaula(): ?int
    {
        return $this->numeroTaula;
    }

    public function setNumeroTaula(int $numeroTaula): self
    {
        $this->numeroTaula = $numeroTaula;

        return $this;
    }

    public function getBye(): ?bool
    {
        return $this->bye;
    }

    public function setBye(bool $bye): self
    {
        $this->bye = $bye;

        return $this;
    }

    public function getRonda(): ?Ronda
    {
        return $this->ronda;
    }

    public function setRonda(?Ronda $ronda): self
    {
        $this->ronda = $ronda;

        return $this;
    }
}
