<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NomCat = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Matiere::class)]
    private Collection $Matieres;

    public function __construct()
    {
        $this->Matieres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCat(): ?string
    {
        return $this->NomCat;
    }

    public function setNomCat(string $NomCat): self
    {
        $this->NomCat = $NomCat;

        return $this;
    }

    /**
     * @return Collection<int, Matiere>
     */
    public function getMatieres(): Collection
    {
        return $this->Matieres;
    }

    public function addMatiere(Matiere $matiere): self
    {
        if (!$this->Matieres->contains($matiere)) {
            $this->Matieres[] = $matiere;
            $matiere->setCategorie($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): self
    {
        if ($this->Matieres->removeElement($matiere)) {
            // set the owning side to null (unless already changed)
            if ($matiere->getCategorie() === $this) {
                $matiere->setCategorie(null);
            }
        }

        return $this;
    }
}
