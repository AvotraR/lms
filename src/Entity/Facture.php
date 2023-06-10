<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $prixTo = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?User $achat = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?Matiere $matiere = null;

 
    public function __construct()
    {
        $this->relation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixTo(): ?int
    {
        return $this->prixTo;
    }

    public function setPrixTo(?int $prixTo): self
    {
        $this->prixTo = $prixTo;

        return $this;
    }

    public function getAchat(): ?User
    {
        return $this->achat;
    }

    public function setAchat(?User $achat): self
    {
        $this->achat = $achat;

        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

   
 

}
