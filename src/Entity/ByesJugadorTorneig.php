<?php

namespace App\Entity;

use App\Repository\ByesJugadorTorneigRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ByesJugadorTorneigRepository::class)
 */
class ByesJugadorTorneig
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Torneig::class)
     */
    private $idTorneig;

    /**
     * @ORM\ManyToOne(targetEntity=Jugador::class)
     */
    private $idJugador;

    /**
     * @ORM\Column(type="integer")
     */
    private $byes;

    /**
     * @ORM\Column(type="float")
     */
    private $punts;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;

    }

    public function getIdTorneig(): ?Torneig
    {
        return $this->idTorneig;
    }

    public function setIdTorneig(?Torneig $idTorneig): self
    {
        $this->idTorneig = $idTorneig;

        return $this;
    }

    public function getIdJugador(): ?Jugador
    {
        return $this->idJugador;
    }

    public function setIdJugador(?Jugador $idJugador): self
    {
        $this->idJugador = $idJugador;

        return $this;
    }

    public function getByes(): ?int
    {
        return $this->byes;
    }

    public function setByes(int $byes): self
    {
        $this->byes = $byes;

        return $this;
    }

    public function __toString()
    {
        return $this->id." ".$this->idTorneig." ".$this->idJugador;
    }

    public function getPunts(): ?float
    {
        return $this->punts;
    }

    public function setPunts(float $punts): self
    {
        $this->punts = $punts;

        return $this;
    }

}
