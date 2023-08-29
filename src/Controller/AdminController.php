<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RegistrationFormType;
use App\Entity\User;
use App\Repository\MatiereRepository;
use App\Repository\FichierRepository;
use App\Repository\FactureRepository;
use App\Entity\Matiere;
use App\Entity\Facture;
use App\Entity\Categorie;
use App\Form\MatiereFType;
use App\Form\CategorieType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\FileUploader;
use Symfony\Component\String\Slugger\SluggerInterface;
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(MatiereRepository $Matiere, UserRepository $user,FichierRepository $fichier,FactureRepository $fact): Response
    {
        $Matieres = $Matiere->findAll();
        $Etudiant = $user->findBy(['role'=>'ROLE_USER']);
        $Prof = $user->findBy(['role'=>'ROLE_PROF']);

        $fich = $fichier->findAll();
        $facture = $fact->findAll();

        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',
            'prof'=>$Prof,
            'etudiant'=>$Etudiant,
            'matiere' => $Matieres,
            'fich'=>$fich,
            'factures'=>$facture
        ]);
    }
    
    #[Route('/admin/liste des formations', name: 'liste_formation')]
    public function listeF(MatiereRepository $Matiere): Response
    {
        $Matieres = $Matiere->findAll();

        return $this->render('admin/listeF.html.twig', [
            'controller_name' => 'AdminController',
            'Matieres' => $Matieres        
        ]);
    }
    #[Route('/admin/liste des etudiants', name: 'liste_etudiant')]
    public function listeE(UserRepository $user): Response
    {
        $Etudiant = $user->findBy(['role'=>'ROLE_USER']);  

        return $this->render('admin/ListeE.html.twig', [
            'controller_name' => 'AdminController',
            'Etudiants' => $Etudiant        
        ]);
    }
    #[Route('/admin/liste des enseignants', name: 'liste_enseignant')]
    public function listeP(UserRepository $user): Response
    {
        $Etudiant = $user->findBy(['role'=>'ROLE_PROF']);

        return $this->render('admin/ListeP.html.twig', [
            'controller_name' => 'AdminController',
            'Etudiants' => $Etudiant        
        ]);
    }
   
    #[Route('/admin/Modification/{id}', name: 'app_mod_etudiant')]
    public function Editer(User $user,Request $request,EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $FichierFile = $form->get('image')->getData();

            if ($FichierFile) {
                $FichierFileName = $fileUploader->upload($FichierFile);
                $user->setImage($FichierFileName);
            }

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('message','utilisateur a ete modifier avec succes');

            return $this->redirectToRoute('liste_etudiant');
        }

        return $this->render('admin/modif.html.twig',[
            'formInscription' => $form->createView(),
            'id' => $user->getId()
        ]);
    }

    
    #[Route('/admin/nouveau_produit', name: 'Nouveau_produit')]
    #[Route('/admin/{id}/modifier', name: 'Modifier_produit')]
    public function form(Matiere $Matiere = null,Request $request,EntityManagerInterface $manager,SluggerInterface $slugger,FileUploader $fileUploader)
    {   
        if(!$Matiere){
            $Matiere = new Matiere();
        }

        $form = $this->createForm(MatiereFType::class, $Matiere);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $Matiere->setCreatedAt(new \DateTimeImmutable);
            $FichierFile = $form->get('ImgMat')->getData();

            if ($FichierFile) {
                $FichierFileName = $fileUploader->upload($FichierFile);
                $Matiere->setImgMat($FichierFileName);
            }

            $manager->persist($Matiere);
            $manager->flush();

            return $this->redirectToRoute('Voir_produit', ['id' => $Matiere->getId()]);
        }

        return $this->render('admin/Nouveau_produit.html.twig',[
            'formMatiere' => $form->createView(),
            'Modifier' => $Matiere->getId() !== null
        ]);
    }

    
    #[Route('/admin/Categorie', name: 'Cat_produit')]
    public function Cat(Request $request,EntityManagerInterface $manager)
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($categorie);
            $manager->flush();
        }
        
        return $this->render('admin/cat.html.twig',[
            'formCat' => $form->createView()
        ]);
    }

    #[Route('/admin/{id}/supprimer', name: 'user_delete')]
    public function delete(Request $request, User $user,EntityManagerInterface $manager,UserRepository $userRepo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepo->remove($user, true);
            $manager->flush();
        }

        return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/admin/{id}/supprimer-achat', name: 'achat_delete')]
    public function deleteAchat(Request $request,Facture $facture,EntityManagerInterface $manager,FactureRepository $factRepo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $factRepo->remove($facture, true);
            $manager->flush();
        }

        return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
    }
}
