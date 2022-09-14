<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServicesController extends AbstractController
{
    #[Route('/spa', name: 'services_spa', methods:['GET'])]
    public function spa(): Response
    {
        return $this->render('services/show_spa.html.twig');
            
        
    }
}
