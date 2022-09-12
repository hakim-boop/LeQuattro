<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChambreController extends AbstractController
{
    #[Route('/voir-chambre', name: 'default_chambre', methods: ['GET'])]
    public function showChambreById(): Response
    {

        return $this->render('room/show_room.html.twig', []);
    }
}
