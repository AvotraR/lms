<?php

 namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

 class UserFixtures extends Fixture
 {

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    
    public function load(ObjectManager $manager){
        
        for ($i=0;$i<100;$i++){
            $user = new User();
            $user->setNom("etudiant");
            $user->setPrenom("etudiant");
            $user->setSexe("Homme");
            $user->setRole("ROLE_USER");
            $user->setAdresse("PARIS");
            $user->setDateDeNaissance(new DateTimeImmutable('now'));
            $user->setEmail("etudiant".random_int(0,10000)."@gmail.com");
            $user->setPassword($this->userPasswordHasher->hashPassword($user,"00000000"));
            $manager->persist($user);
            $manager->flush();
        }
    }
 } 