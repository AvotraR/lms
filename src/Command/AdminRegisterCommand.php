<?php

namespace App\Command;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminRegisterCommand extends Command{

    protected EntityManagerInterface $manager;
    protected UserPasswordHasherInterface $encoder;

    protected static $defaultName = 'lms:create:admin';

    public function __construct(EntityManagerInterface $manager, UserPasswordHasherInterface $encoder){
        $this->manager = $manager;
        $this->encoder = $encoder;
        parent::__construct();
    }

    public function configure(){
        $this->setDescription("Creation d'une utilisateur Administrateur");
    }

    public function execute(InputInterface $input,OutputInterface $output){
        $style = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');
        $nom = $helper->ask($input, $output, new Question('Nom : '));
        $prenom = $helper->ask($input, $output, new Question('Prenom : '));
        $adresse = $helper->ask($input, $output, new Question('Adresse : '));
        $sexe = $helper->ask($input, $output, new Question('Sexe : '));
        $passWord = $helper->ask($input, $output, new Question('Mots de passe : '));
        $email = $helper->ask($input, $output, new Question('email : '));

        $user = new User();
        $user
            ->setNom($nom)
            ->setPrenom($prenom)
            ->setAdresse($adresse)
            ->setSexe($sexe)
            ->setRole("ROLE_ADMIN")
            ->setDateDeNaissance(new DateTimeImmutable('now'))
            ->setPassword($this->encoder->hashPassword($user, $passWord))            
            ->setEmail($email);

        $this->manager->persist($user);
        $this->manager->flush();

        $style->success(' Création de l\'Admin '.$nom.' réussi');

        return 1;

    }
}
