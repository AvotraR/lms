<?php

namespace App\Entity;
use Doctrine\DBAL\Types\Types;

Class Recherche{
    private ?int $PrixMin = null;

    private ?int $PrixMax = null;

    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;
   
    public function getPrixMin(): ?int
    {
        return $this->PrixMin;
    }

    public function setPrixMin(int $PrixMin): self
    {
        $this->PrixMin = $PrixMin;

        return $this;
    }
    
    public function getPrixMax(): ?int
    {
        return $this->PrixMax;
    }

    public function setPrixMax(int $PrixMax): self
    {
        $this->PrixMax = $PrixMax;

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
}
