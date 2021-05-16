<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FilmRepository::class)
 */
class Film
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("film:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=3,minMessage="la longeur de votre nom est inssufisante ")
     * @Assert\NotBlank()
     * @Groups("film:read")
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=8,minMessage="la longeur de votre nom est inssufisante ")
     * @Groups("film:read")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Groups("film:read")
     */
    private $duree;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     * @Groups("film:read")
     */
    private $datesortie;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     * @Groups("film:read")
     */
    private $note;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=8,minMessage="la longeur de votre nom est inssufisante ")
     * @Groups("film:read")
     */
    private $realisepar;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups("film:read")
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieFilm::class, inversedBy="films")
     */
    private $categorie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDuree(): ?\DateTimeInterface
    {
        return $this->duree;
    }

    public function setDuree(\DateTimeInterface $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDatesortie(): ?\DateTimeInterface
    {
        return $this->datesortie;
    }

    public function setDatesortie(\DateTimeInterface $datesortie): self
    {
        $this->datesortie = $datesortie;

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getRealisepar(): ?string
    {
        return $this->realisepar;
    }

    public function setRealisepar(string $realisepar): self
    {
        $this->realisepar = $realisepar;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCategorie(): ?CategorieFilm
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieFilm $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
