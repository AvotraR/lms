<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MatiereRepository;
use App\Entity\Matiere;
use App\Entity\Facture;
use App\Entity\Recherche;
use App\Service\factureService;
use App\Form\MatiereFType;
use App\Form\RechercheType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\FileUploader;

class LmsController extends AbstractController
{
    
    #[Route('/', name: 'app_lms')]
    public function index(SessionInterface $session)
    {  
        return $this->render('lms/index.html.twig');
        
    }
    #[Route('/produit', name: 'produits')]
    public function Produit(MatiereRepository $matiereRepo,Request $request)
    {
        $Matieres = $matiereRepo->findAll();
        $recherche = new Recherche();
        $form = $this->createForm(RechercheType::class, $recherche);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $Matieres = $matiereRepo->search($recherche);
        }
        return $this->render('lms/produits.html.twig', [
            'controller_name' => 'LmsController',
            'Matieres' => $Matieres,
            'form' => $form->createView()
        ]);
        
    }

    #[Route('/{{id}}', name: 'Voir_produit')]
    public function Voir_produit(MatiereRepository $matiereRepo, $id)
    {
        $Matiere = $matiereRepo->find($id);
        return $this->render('lms/Voir_produit.html.twig',[
            'Matiere' => $Matiere
        ]);
    }
    #[Route('/panier/achat', name: 'achat')]
    public function Afficher_achat(MatiereRepository $MatiereRepo,SessionInterface $session)
    {
        
        $panier = $session->get("panier", []);
        $dataPanier = [];
        $total = 0;
        foreach($panier as $id => $quantite){
            $Matiere = $MatiereRepo->find($id);
            $dataPanier[] = [
               "matiere" =>  $Matiere,
               "Quantite" => $quantite
            ];
            $total += $Matiere->getPrixMat() * $quantite;
        }
        return $this->render('lms/panier.html.twig',compact("dataPanier","total"));
    }
    #[Route('/panier/validation', name: 'app_validation')]
    public function Validation_achat(MatiereRepository $MatiereRepo,SessionInterface $session)
    {
        $user = $this->getUser();
        $panier = $session->get("panier",[]);
        $dataPanier = [];
        $total = 0;
        foreach($panier as $id => $quantite){
            $Matiere = $MatiereRepo->find($id);
            $dataPanier []= [
               "matiere" =>  $Matiere,
               "Quantite" => $quantite
            ];
            $total += $Matiere->getPrixMat() * $quantite;
              
         }
         return $this->render('/lms/validation.html.twig',compact("dataPanier","user","total"));
    }
    #[Route('/panier/ajouter/{{id}}', name: 'ajout_prod')]
    public function ajouter_achat( $id ,SessionInterface $session)
    {
       
        $panier = $session->get("panier", []);
        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }
        $session->set("panier",$panier);
        return $this->redirectToRoute('achat');
    }
    #[Route('/panier/suppression/{{id}}', name: 'suppr_prod')]
    public function supprimer_achat( $id ,SessionInterface $session)
    {
       
        $panier = $session->get("panier", []);
        if(!empty($panier[$id])){
            if($panier[$id]>1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }
        $session->set("panier",$panier);
        return $this->redirectToRoute('achat');
    }
    #[Route('/panier/supprimerTout/', name: 'supAll_prod')]
    public function supprimer_tout(SessionInterface $session)
    {
        $session->remove("panier");
        return $this->redirectToRoute('achat');
    }
    #[Route('/panier/payer', name: 'app_payer')]
    public function payer( request $request,SessionInterface $session,MatiereRepository $MatiereRepo,factureService $facture)
    {
       $user = $this->getUser();
       $panier = $session->get("panier",[]);
       $dataPanier = [];
       $total = 0;
       foreach($panier as $id => $quantite){
           $Matiere = $MatiereRepo->find($id);
           $dataPanier []= [
              "matiere" =>  $Matiere,
              "Quantite" => $quantite
           ];
           $total += $Matiere->getPrixMat() * $quantite;
             
        }
        $facture = $facture->facture($dataPanier,$total,$user); 
        ?><script>alert('Votre paiement a été bien soumis!')</script><?php
        return $this->render('/lms/validation.html.twig',compact("dataPanier","user","total"));
    }

}
