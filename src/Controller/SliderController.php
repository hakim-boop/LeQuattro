<?php

namespace App\Controller;

use DateTime;
use App\Entity\Slider;
use App\Form\SliderFormType;
use App\Repository\SliderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

        if ($form->isSubmitted() && $form->isValid()) {

            $slider->setCreatedAt(new DateTime);
            $slider->setUpdatedAt(new DateTime);

            # On variabilise le fichier de la photo dans $photo.
            # On obtient un objet de type UploadedFile()
            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            if ($photo) {

                $this->handleFile($slider, $photo, $slugger);
            } // end if $photo

            $entityManager->persist($slider);
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez ajouté une photo dans le carrousel !');
            return $this->redirectToRoute('show_slider');
        } // end if $form

        return $this->render('admin/create_slider.html.twig', [
            'form' => $form->createView()
        ]);
    } //? end create

    // ? fonction update
    #[Route('/modifier-un-slider/{id}', name: 'update_slider', methods: ['GET', 'POST'])]
    public function updateProduit(Slider $slider, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        // ? recuperation de la photo actuelle
        $originalPhoto = $slider->getPhoto();
        $form = $this->createForm(SliderFormType::class, $slider, [
            'photo' => $originalPhoto
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slider->setUpdatedAt(new DateTime());
            $photo = $form->get('photo')->getData();

            if ($photo) {
                $this->handleFile($slider, $photo, $slugger);
            } else {
                $slider->setPhoto($originalPhoto);
            }

            $entityManager->persist($slider);
            $entityManager->flush($slider);

            $this->addFlash('success', 'Le slider est bien modifié !');
            return $this->redirectToRoute('show_slider');
        }

        return $this->render('admin/update_slider.html.twig', [
            'form' => $form->createView(),
            'slider' => $slider
        ]);
    } // ? end function update()

    // ? fonction softDelete()
    #[Route('/archiver-une-photo/{id}', name: 'soft_delete_slider', methods: ['GET'])]
    public function softDeleteProduit(Slider $slider, EntityManagerInterface $entityManager): RedirectResponse
    {
        $slider->setDeletedAt(new dateTime());

        $entityManager->persist($slider);
        $entityManager->flush($slider);

        $this->addFlash('success', 'La photo a bien été archivée !');
        return $this->redirectToRoute('show_slider');
    }
    // ? end fonction softDelete()

    // ************************************ PRIVATE FUNCTION *********************************************

    private function handleFile(Slider $slider, UploadedFile $photo, SluggerInterface $slugger): void
    {

        $extension = '.' . $photo->guessExtension();

        $safeFilename = $slugger->slug($photo->getClientOriginalName());
        //                $safeFilename = $slugger->slug($produit->getTitle());

        $newFilename = $safeFilename . '_' . uniqid() . $extension;


        try {
            $photo->move($this->getParameter('uploads_dir'), $newFilename);
            $slider->setPhoto($newFilename);
        } catch (FileException $exception) {
            $this->addFlash('warning', 'La photo du produit ne s\'est pas importée avec succès. Veuillez réessayer.');
            // return $this->redirectToRoute('create_produit');
        }
    }
} //? end CLass
