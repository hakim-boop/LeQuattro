<?php

namespace App\Controller;

use DateTime;
use App\Entity\Chambre;
use App\Entity\Category;
use App\Entity\Commande;
use App\Form\ChambreFormType;
use App\Form\CommandeFormType;
use App\Repository\ChambreRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\Loader\Configurator\form;




class CommandeController extends AbstractController

{

     #[Route('/voir-les-commandes', name: 'show_commandes', methods: ['GET'])]
     public function showCommandes(EntityManagerInterface $entityManager): Response
     {
         $commandes = $entityManager->getRepository(Commande::class)->findBy(['deletedAt' => null]);
 
         return $this->render('admin/show_commandes.html.twig', [
             'commandes' => $commandes,
         ]);
     }
    // start function create() CETTE METHODE(fonction) EST A METTRE DANS ADMIN CONTROLLER !!!   
    #[Route('/admin/ajouter-une-commande', name: 'create_commande', methods: ['GET', 'POST'])]
    public function createCommande( Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
           $commande = new Commande();
           
   
           $form = $this->createForm(CommandeFormType::class, $commande)
               ->handleRequest($request);
   
           if ($form->isSubmitted() && $form->isValid()) {
   
               $commande->setCreatedAt(new DateTime);
               $commande->setUpdatedAt(new DateTime);
   

            //    $photo = $form->get('photo')->getData();
   
            //    if ($photo) {
                 
            //        $this->handleFile($commande, $photo, $slugger);
            //    } // end if $photo
   
               $entityManager->persist($commande);
               $entityManager->flush();
   
               $this->addFlash('success', 'La commande ajoutée avec succès !');
               return $this->redirectToRoute('show_dashboard');
           } // end if $form
   
           return $this->render('admin/form/create_commande.html.twig', [
               'form' => $form->createView()
           ]);
       } // end function create() CETTE METHODE(fonction) EST A METTRE DANS ADMIN CONTROLLER !!!
       
    ////////////////////////////////////////////////////////  FONCTIONS CREES PAR NOUS MEME /////////////////////////////////////////////////////////////


    // private function handleFile(Commande $commande, UploadedFile $photo, SluggerInterface $slugger): void
    // {

    //     $extension = '.' . $photo->guessExtension();


    //     $safeFilename = $slugger->slug($photo->getClientOriginalName());

    //     $newFilename = $safeFilename . '_' . uniqid() . $extension;


    //     try {

    //         $photo->move($this->getParameter('uploads_dir'), $newFilename);

    //         $commande->setPhoto($newFilename);
    //     } catch (FileException $exception) {
    //         $this->addFlash('warning', 'La photo de la chambre ne s\'est pas importée avec succès. Veuillez réessayer.');
    //     }
    // } // end class handleFile()

}
