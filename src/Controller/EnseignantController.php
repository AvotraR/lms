<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Fichier;
use App\Entity\Matiere;
use App\Entity\Comment;
use App\Form\FichierType;
use App\Entity\Prof;
use App\Form\ProfType;
use App\Form\CommentType;
use App\Repository\UserRepository;
use App\Repository\MatiereRepository;
use App\Repository\FichierRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\FileUploader;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class EnseignantController extends AbstractController
{
    #[Route('/enseignant', name: 'app_enseignant')]
    public function index(MatiereRepository $MatiereRepo): Response
    {
        $user= $this->getUser();
        $id = $user->getId();
        $Matiere =$MatiereRepo->findBy(['Enseigner'=>$id]);
        return $this->render('enseignant/index.html.twig', [
            'controller_name' => 'EnseignantController',
            'Matiere' => $Matiere
        ]);
    }

    #[Route('/enseignant/envoye/{{id}}', name: 'enseignant_fichier')]
    public function envoie(Matiere $Matiere,$id,Request $request,EntityManagerInterface $manager,SluggerInterface $slugger,FileUploader $fileUploader): Response
    {
          $user = $this->getUser();
            $Fichier = new Fichier();
            $form = $this->createForm(FichierType::class, $Fichier);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $FichierFile = $form->get('fichier')->getData();
                if ($FichierFile) {
                    $FichierFileName = $fileUploader->upload($FichierFile);
                    $Fichier->setFichier($FichierFileName);
                }
                
                $Fichier->setUser($user)
                        ->setMatiere($Matiere);
        
                $manager->persist($Fichier);
                $manager->flush();
            }
            return $this->render('enseignant/fichier.html.twig', [
                'controller_name' => 'EnseignantController',
                'formFichier' => $form->createView(),
                'matiere' => $Matiere,
                'user'=>$user
            ]);
        }
        #[Route('/enseignant/{{id}}', name: 'app_videoEnseignant')]
        public function enseignantVid(Fichier $Fichier,MatiereRepository $MatiereRepo,FichierRepository $FichierRepo,$id,Request $request,EntityManagerInterface $manager): Response
        {
                        $Fich = $FichierRepo->find($id);
                        $user = $this->getUser();
                        $Comment = new Comment();
                        $form = $this->createForm(CommentType::class, $Comment);
                        $form->handleRequest($request);
                        if($form->isSubmitted() && $form->isValid()){
                            $Comment->setCreatedAt(new \DateTimeImmutable)
                                    ->setAuteur($user)
                                    ->setFich($Fichier);
                    
                            $manager->persist($Comment);
                            $manager->flush();
                        }
                        return $this->render('enseignant/videoEnseignant.html.twig', [
                            'controller_name' => 'EtudiantController',
                            'formComment' => $form->createView(),
                            'fich' => $Fich,
                            'fichier'=>$Fichier,
                            'user'=>$user
                        ]);
        }
       
}
