<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\FormationController;
use App\Repository\FormationnRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: FormationnRepository::class)]
#[ApiResource(
    new Get(
        name: 'ListFormation',
        uriTemplate: '/formations/list',
        controller: FormationController::class . '::index'
    )
)]
class Formationn
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getFormations", "getCandidatures"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["getFormations", "getCandidatures"])]
    #[Assert\NotBlank(message: "Le statut de la formation est obligatoire")]
    #[Assert\Type(type: 'bool', message: 'Le statut doit être un booléen')]
    private ?bool $statut = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getFormations", "getCandidatures"])]
    #[Assert\NotBlank(message: "Le titre de la formation est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le titre doit faire au moins {{ limit }} caractères", maxMessage: "Le titre ne peut pas faire plus de {{ limit }} caractères")]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getFormations", "getCandidatures"])]
    #[Assert\NotBlank(message: "Les criteres de la formation est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "Les criteres de la formation doivent faire au moins {{ limit }} caractères", maxMessage: "Les criteres de la formation ne peuvent pas faire plus de {{ limit }} caractères")]
    private ?string $criteres = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["getFormations", "getCandidatures"])]
    #[Assert\NotNull(message: "La durée de la formation ne peut pas être vide")]
    #[Assert\Type(type: 'integer', message: 'La durée doit être un nombre')]
    private ?int $duree = null;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: Candidature::class)]
    // #[Groups(["getCandidatures"])]
    private Collection $candidatures;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
    }

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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getCriteres(): ?string
    {
        return $this->criteres;
    }

    public function setCriteres(string $criteres): static
    {
        $this->criteres = $criteres;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * @return Collection<int, Candidature>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): static
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures->add($candidature);
            $candidature->setFormation($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): static
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getFormation() === $this) {
                $candidature->setFormation(null);
            }
        }

        return $this;
    }
}
