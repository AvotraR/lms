<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Matiere;
use App\Entity\Fichier;
use App\Entity\Categorie;
use App\Entity\Reclamation;

class MatiereFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $Cat1 = new Categorie();
        $Cat1->setNomCat("Informatique");
        $manager ->persist($Cat1);
        $manager->flush();
        $Cat2 = new Categorie();
        $Cat2->setNomCat("Musique");
        $manager ->persist($Cat2);
        $manager->flush();
        $Cat3 = new Categorie();
        $Cat3->setNomCat("Langue");
        $manager ->persist($Cat3);
        $manager->flush();


    }
}
