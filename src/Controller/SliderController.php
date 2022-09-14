<?php

namespace App\Controller;

use DateTime;
use App\Entity\Slider;
use App\Form\SliderFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin')]
class SliderController extends AbstractController
{
    #[Route('/voir-les-photos', name: 'show_slider', methods: ['GET'])]
    public function showSlider(EntityManagerInterface $entityManager): Response
    {
        $slider = $entityManager->getRepository(Slider::class)->findBy(['deletedAt' => null]);

        return $this->render('admin/show_slider.html.twig', [
            'slider' => $slider
        ]);

    } // end function show()



    #[Route('/ajouter-une-photo', name: 'create_slider', methods: ["GET", 'POST'])]
    public function createPhoto(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $slider = new Slider();

        $form = $this->createForm(SliderFormType::class, $slider)
            ->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {

        //     $slider->setCreatedAt(new DateTime);
        //     $slider->setUpdatedAt(new DateTime);

        //     # On variabilise le fichier de la photo dans $photo.
        //     # On obtient un objet de type UploadedFile()
        //     /** @var UploadedFile $photo */
        //     $photo = $form->get('photo')->getData();

        //     if ($photo) {

        //         $this->handleFile($slider, $photo, $slugger);
        //     } // end if $photo

        //     $entityManager->persist($slider);
        //     $entityManager->flush();

        //     $this->addFlash('success', 'Vous avez ajouté une photo dans le carrousel !');
        //     return $this->redirectToRoute('show_slider');
        // } // end if $form

        return $this->render('admin/create_slider.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // // ************************************ PRIVATE FUNCTION *********************************************

    // private function handleFile(Slider $slider, UploadedFile $photo, SluggerInterface $slugger): void
    // {
    //     # 1 - Déconstruire le nom du fichier
    //     # a - On récupère l'extension grâce à la méthode guessExtension()
    //     $extension = '.' . $photo->guessExtension();

    //     # 2 - Sécuriser le nom et reconstruire le nouveau nom du fichier
    //     # a - On assainit le nom du fichier pour supprimer les espaces et les accents.
    //     $safeFilename = $slugger->slug($photo->getClientOriginalName());
    //     //                $safeFilename = $slugger->slug($produit->getTitle());

    //     # b - On reconstruit le nom du fichier
    //     # uniqid() est une fonction native de PHP et génère un identifiant unique.
    //     # Cela évite les possibilités de doublons
    //     $newFilename = $safeFilename . '_' . uniqid() . $extension;

    //     # 3 - Déplacer le fichier dans le bon dossier.
    //     // ? On utilise un try/catch lorsqu'une méthode "throws" (lance) une Exception (erreur)
    //     try {
    //         $photo->move($this->getParameter('uploads_dir'), $newFilename);
    //         $slider->setPhoto($newFilename);
    //     } catch (FileException $exception) {
    //         $this->addFlash('warning', 'La photo du produit ne s\'est pas importée avec succès. Veuillez réessayer.');
    //         // return $this->redirectToRoute('create_produit');
    //     }
    // }

} //? end CLass
