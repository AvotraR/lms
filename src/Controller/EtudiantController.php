<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FactureRepository;
use App\Repository\MatiereRepository;
use App\Repository\FichierRepository;
use App\Entity\Matiere;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Fichier;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;


class EtudiantController extends AbstractController
{
    #[Route('/etudiant', name: 'etudiant')]
    public function etudiantM(FactureRepository $factureRepo): Response
    {
        $user = $this->getUser();
        $id = $user->getId();
        $facture = $factureRepo->findBy(['achat'=>$id]);
        
        return $this->render('etudiant/Etudiant.html.twig', [
            'controller_name' => 'EtudiantController',
            'facture' => $facture
        ]);
    }
    #[Route('/etudiant/apprendre/{{id}}', name: 'etudiant_app')]
    public function etudiantApp(Request $request,MatiereRepository $MatiereRepo, $id): Response
    {
        if ($this->isCsrfTokenValid('matiere'.$id, $request->request->get('_token'))) {

            $Matiere = $MatiereRepo->find($id);

            return $this->render('etudiant/apprendre.html.twig', [
                'controller_name' => 'EtudiantController',
                'Matiere' => $Matiere
            ]);

        }else{
            ?><script>alert('Attention ne fait pas cela!!!');</script><?php

            return $this->redirectToRoute('etudiant', [], Response::HTTP_SEE_OTHER);
        }
    }
    #[Route('/etudiant/{{id}}', name: 'video_app')]
    public function etudiantVid(Fichier $Fichier,MatiereRepository $MatiereRepo,FichierRepository $FichierRepo,$id,Request $request,EntityManagerInterface $manager): Response
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
        
        return $this->render('etudiant/video.html.twig', [
                'controller_name' => 'EtudiantController',
                'formComment' => $form->createView(),
                'fich' => $Fich,
                'fichier'=>$Fichier,
                'user'=>$user
        ]);
             
    }
 
}
