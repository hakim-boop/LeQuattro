<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServicesController extends AbstractController
{
    #[Route('/restaurant', name: 'restaurant', methods:['GET'])]
    public function restaurant(): Response
    {
        return $this->render('services/restaurant.html.twig');
    }
}
