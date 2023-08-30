<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Fichier;
use App\Entity\Matiere;
use App\Entity\Categorie;
use App\Entity\Reclamation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Formation extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $Cat3 = new Categorie();
        $Cat3->setNomCat("Info");
        $manager ->persist($Cat3);
        $manager->flush();

        for ($i=0;$i<30;$i++){

            $user = new User();
            $user->setNom("prof");
            $user->setPrenom("Prof");
            $user->setSexe("Homme");
            $user->setRole("ROLE_PROF");
            $user->setAdresse("Anosy");
            $user->setDateDeNaissance(new DateTimeImmutable('now'));
            $user->setEmail("prof".random_int(0,1000)."@gmail.com");
            $user->setPassword($this->userPasswordHasher->hashPassword($user,"00000000"));
            $manager->persist($user);
            
            $Matiere = new Matiere();
            $Matiere->setNomMat("Formation".random_int(10,1000));
            $Matiere->setPrixMat(random_int(100000,300000));
            $Matiere->setDetailMat("fdgsdfgdfglfkjgskld=fdlfjhdlksjfhqsdkl=");
            $Matiere->setCategorie($Cat3);
            $Matiere->setImgMat("dfqsdfsdf.jpg");
            $Matiere->setCreatedAt(new DateTimeImmutable('now'));
            $Matiere->setEnseigner($user);
            $manager ->persist($Matiere);

            $manager->flush();
        }
    }
}
