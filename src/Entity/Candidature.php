<?php

namespace App\Entity;

use App\Repository\CandidatureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
#[ApiResource]
#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
class Candidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getFormations", "getCandidatures", "getUsers"])] 
    private ?int $id = null;
    
    #[ORM\Column]   
    #[Groups(["getFormations", "getCandidatures", "getUsers"])] 
    #[Assert\NotBlank(message: "Le statut de la candidature est obligatoire")]
    #[Assert\Type(type: 'bool', message: 'Le statut doit être un booléen')]
     private ?bool $statut = null;
     
     #[ORM\ManyToOne(inversedBy: 'candidatures')]
    #[Groups("getCandidatures")]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "La formation ne peut pas être vide")]
    private ?Formationn $formation = null;
    
    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    #[Groups(["getCandidatures"])]  //, "getUsers"  "getFormations", 
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Le Candidat ne peut pas être vide")]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getFormation(): ?Formationn
    {
        return $this->formation;
    }

    public function setFormation(?Formationn $formation): static
    {
        $this->formation = $formation;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
