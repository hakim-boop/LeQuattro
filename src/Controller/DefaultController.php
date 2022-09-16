<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_home', methods:['GET'])]
    public function home(): Response
    {
        return $this->render('default/home.html.twig');
            
    }

    #[Route('/hôtel', name: 'show_hotel', methods:['GET'])]
    public function hotel(): Response
    {
        return $this->render('default/show_hotel.html.twig');
            
        
    }

    
}
