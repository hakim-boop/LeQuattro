<?php

namespace App\Controller;

use DateTime;
use App\Entity\Chambre;
use App\Entity\Commande;
use App\Form\CommandeFormType;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{       
    ////////////////////////////////////////////////////////  FONCTIONS CREES PAR NOUS MEME /////////////////////////////////////////////////////////////
    // start function create() COMANDES CETTE METHODE(fonction) EST A METTRE DANS ADMIN CONTROLLER !!!   
    #[Route('/reserver-une-chambre/{id}', name: 'create_commande', methods: ['GET', 'POST'])]
    public function createCommande(Chambre $chambre, Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
    
        // $chambre = $entityManager->getRepository(Chambre::class)->findBy(['deletedAt'=>null]);

        $form = $this->createForm(CommandeFormType::class, $commande)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $commande->setCreatedAt(new DateTime);
            $commande->setUpdatedAt(new DateTime);

            $commande->setChambre($chambre);

            $prixDeBase = 180;
          
            $diff = $commande->getDateDebut()->diff($commande->getDateFin(), true);
            
            dd($diff);
            $prixTotalNuits = (int)$prixDeBase * (int)$diff;

            $commande->setPrixTotal($prixTotalNuits);

           
             
            

            $entityManager->persist($commande);
            $entityManager->flush();

            $this->addFlash('success', 'La commande ajoutée avec succès !');
            return $this->redirectToRoute('show_dashboard');
        } // end if $form

        return $this->render('chambre/create_commande.html.twig', [
            'form' => $form->createView()
        ]);
    } 
}