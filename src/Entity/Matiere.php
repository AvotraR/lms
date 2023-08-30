<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MatiereRepository::class)]
class Matiere
{
    #[Vich\Uploadable]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:3, max:255, minMessage:"Votre titre est trop court!")]
    private ?string $NomMat = null;

    #[ORM\Column]
    #[Assert\Range(min:6,minMessage:"Votre prix est trop bas!")]
    private ?int $PrixMat = null;

    #[ORM\Column(length: 255)]
    private ?string $ImgMat = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(min:10,minMessage:"Votre text est trop court!")]
    private ?string $DetailMat = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'Matieres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;


    #[ORM\ManyToOne(inversedBy: 'matieres')]
    private ?User $Enseigner = null;

    #[ORM\OneToMany(mappedBy: 'matiere', targetEntity: Facture::class)]
    private Collection $factures;

    #[ORM\OneToMany(mappedBy: 'Matiere', targetEntity: Fichier::class)]
    private Collection $fichiers;

  
 
    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
        $this->factures = new ArrayCollection();
        $this->fichiers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMat(): ?string
    {
        return $this->NomMat;
    }

    public function setNomMat(string $NomMat): self
    {
        $this->NomMat = $NomMat;

        return $this;
    }

    public function getPrixMat(): ?int
    {
        return $this->PrixMat;
    }

    public function setPrixMat(int $PrixMat): self
    {
        $this->PrixMat = $PrixMat;

        return $this;
    }

    public function getImgMat(): ?string
    {
        return $this->ImgMat;
    }

    public function setImgMat(string $ImgMat): self
    {
        $this->ImgMat = $ImgMat;

        return $this;
    }

   

    public function getDetailMat(): ?string
    {
        return $this->DetailMat;
    }

    public function setDetailMat(string $DetailMat): self
    {
        $this->DetailMat = $DetailMat;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getEnseigner(): ?User
    {
        return $this->Enseigner;
    }

    public function setEnseigner(?User $Enseigner): self
    {
        $this->Enseigner = $Enseigner;

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures[] = $facture;
            $facture->setMatiere($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getMatiere() === $this) {
                $facture->setMatiere(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fichier>
     */
    public function getFichiers(): Collection
    {
        return $this->fichiers;
    }

    public function addFichier(Fichier $fichier): self
    {
        if (!$this->fichiers->contains($fichier)) {
            $this->fichiers[] = $fichier;
            $fichier->setMatiere($this);
        }

        return $this;
    }

    public function removeFichier(Fichier $fichier): self
    {
        if ($this->fichiers->removeElement($fichier)) {
            // set the owning side to null (unless already changed)
            if ($fichier->getMatiere() === $this) {
                $fichier->setMatiere(null);
            }
        }

        return $this;
    }


}
