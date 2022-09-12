<?php

namespace App\Controller;

use App\Entity\Membre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}