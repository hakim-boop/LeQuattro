<?php

namespace App\Controller;

use DateTime;
use App\Entity\Membre;
use App\Form\UpdateMembreFormType;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/tableau-de-bord', name: 'show_dashboard', methods: ['GET'])]
    public function showDashboard(EntityManagerInterface $entityManager): Response
    {
        $membres = $entityManager->getRepository(Membre::class)->findAll();

        return $this->render('admin/show_dashboard.html.twig', [
            'membres' => $membres,
        ]);
    }

    #[Route("/modifier-un-membre/{id}", name: 'update_membre', methods: ['GET', 'POST'])]
    public function updateMembre(Membre $membre,Request $request, MembreRepository $repository): Response
    {
        
        $form = $this->createForm(UpdateMembreFormType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $membre = $repository->find($membre->getId());

            $membre->setRoles([$form->get('roles')->getData()]);
            
            $membre->setUpdatedAt(new DateTime());

            $repository->add($membre, true);

            $this->addFlash('success', 'Votre modification a été effectuée avec succès !');
            return $this->redirectToRoute('show_dashboard');
        }

        return $this->render('admin/form/update_membre.html.twig', [
            'membre' => $membre,
            'form' => $form->createView()
        ]);
    }
}
