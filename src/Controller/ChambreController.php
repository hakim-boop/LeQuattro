<?php

namespace App\Controller;

use DateTime;
use App\Entity\Chambre;
use App\Form\ChambreFormType;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class ChambreController extends AbstractController
{
     #[Route('/voir-chambre', name: 'show_chambre', methods: ['GET'])]   
     public function showChambre( ChambreRepository $repository, EntityManagerInterface $entityManager): Response
        {
        $chambre = $entityManager->getRepository(Chambre::class)->findBy(['deletedAt' => null]);

        return $this->render('chambre/show_chambre.html.twig', [
            'chambre' => $chambre,

        ]);

        } // end if $chambre
        
        #[Route('/ajouter-une-chambre', name: 'create_chambre', methods: ['GET', 'POST'])]
        public function createChambre(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
        {
            $chambre = new Chambre();
    
            $form = $this->createForm(ChambreFormType::class, $chambre)
                ->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
    
                $chambre->setCreatedAt(new DateTime);
                $chambre->setUpdatedAt(new DateTime);
    

                $photo = $form->get('photo')->getData();
    
                if ($photo) {
                  
                    $this->handleFile($chambre, $photo, $slugger);
                } // end if $photo
    
                $entityManager->persist($chambre);
                $entityManager->flush();
    
                $this->addFlash('success', 'La chambre ajoutée avec succès !');
                return $this->redirectToRoute('create_chambre');
            } // end if $form
    
            return $this->render('chambre/create_chambre.html.twig', [
                'form' => $form->createView()
            ]);
        } // end function create()


////////////////////////////////////////////////////////  FONCTIONS CREES PAR NOUS MEME /////////////////////////////////////////////////////////////


        private function handleFile(Chambre $chambre, UploadedFile $photo, SluggerInterface $slugger): void
        {

            $extension = '.' . $photo->guessExtension();
    

            $safeFilename = $slugger->slug($photo->getClientOriginalName());

            $newFilename = $safeFilename . '_' . uniqid() . $extension;
    
  
            try {

                $photo->move($this->getParameter('uploads_dir'), $newFilename);

                $chambre->setPhoto($newFilename);
            } catch (FileException $exception) {
                $this->addFlash('warning', 'La photo de la chambre ne s\'est pas importée avec succès. Veuillez réessayer.');
                //            return $this->redirectToRoute('create_chambre');
            }
        } // end class handleFile()
    
}
