<?php

namespace App\Controller;

use DateTime;
use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact', methods: ['GET', 'POST'])]
    public function contact(Request $request, ContactRepository $repository): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactFormType::class, $contact)
        ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $contact->setCreatedAt(new DateTime());
            $contact->setUpdatedAt(new DateTime());

            $repository->add($contact, true);

            $this->addFlash('success', 'Votre demande a été effectuée avec succès !');
            return $this->redirectToRoute('default_home');
        }

        return $this->render('render/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }    
}
