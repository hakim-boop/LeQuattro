<?php

namespace App\Controller;

use DateTime;
use App\Entity\Avis;
use App\Form\AvisFormType;
use App\Repository\AvisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'avis', methods: ['GET', 'POST'])]
    public function avis(Request $request, AvisRepository $repository): Response
    {
        $avis = new Avis();

        $form = $this->createForm(AvisFormType::class, $avis)
        ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $avis->setCreatedAt(new DateTime());
            $avis->setUpdatedAt(new DateTime());

            $repository->add($avis, true);

            $this->addFlash('success', 'Votre demande a été effectuée avec succès !');
            return $this->redirectToRoute('default_home');
        }

        return $this->render('render/avis.html.twig', [
            'form' => $form->createView()
        ]);
    }    
}
