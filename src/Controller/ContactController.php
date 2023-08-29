<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;
Use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request,EntityManagerInterface $manager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($Fichier);
            $manager->flush();
            
            ?><script>alert('Message envoyer...!')</script><?php
        }

        return $this->render('contact/contact.html.twig', [
            'controller_name' => 'ContactController',
            'formContact' =>$form->createView(),
        ]);
    }
}
