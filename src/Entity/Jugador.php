<?php

namespace App\Entity;

use App\Repository\JugadorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JugadorRepository::class)
 */
class Jugador
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
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cognoms;

    /**
     * @ORM\Column(type="integer")
     */
    private $elo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pais;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $club;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dni;

    /**
     * @ORM\Column(type="date")
     */
    private $dataNaixament;

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

    public function getCognoms(): ?string
    {
        return $this->cognoms;
    }

    public function setCognoms(string $cognoms): self
    {
        $this->cognoms = $cognoms;

        return $this;
    }

    public function getElo(): ?int
    {
        return $this->elo;
    }

    public function setElo(int $elo): self
    {
        $this->elo = $elo;

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

    public function getClub(): ?string
    {
        return $this->club;
    }

    public function setClub(string $club): self
    {
        $this->club = $club;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    public function getDataNaixament(): ?\DateTimeInterface
    {
        return $this->dataNaixament;
    }

    public function setDataNaixament(\DateTimeInterface $dataNaixament): self
    {
        $this->dataNaixament = $dataNaixament;

        return $this;
    }

    public function __toString()
    {
        return $this->nom." ".$this->cognoms;
    }
}
