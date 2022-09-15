<?php

namespace App\Controller;

use DateTime;
use App\Entity\Avis;
use App\Form\AvisFormType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'avis', methods: ['GET', 'POST'])]
    public function avis(Request $request, AvisRepository $repository): Response
    {
        $opinion = new Avis();

        $form = $this->createForm(AvisFormType::class, $opinion)
        ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $opinion->setCreatedAt(new DateTime());
            $opinion->setUpdatedAt(new DateTime());

            $repository->add($opinion, true);

            $this->addFlash('success', 'Votre avis a été poster avec succès !');
            return $this->redirectToRoute('show_avis');
        }

        return $this->render('rendered/avis.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    #[Route('/voir-les-avis', name: 'show_avis', methods: ['GET'])]
    public function showAvis(EntityManagerInterface $entityManager): Response
    {
        $opinions = $entityManager->getRepository(Avis::class)->findBy([
            "deletedAt" => null,
        ]);

        return $this->render('rendered/show_avis.html.twig', [
            'opinions' => $opinions,
        ]);
    }
}
