<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RenderController extends AbstractController 

{

// start function showBackofficeCategory() 
    #[Route('/voir-backoffice-category', name: 'show_category', methods: ['GET'])]
    public function showBackofficeCategory( CategoryRepository $repository): Response
    {   
    $category = new Category();
    

    $categories = $repository->findBy(['deletedAt' => null], ['name' => 'ASC']);
    
    return $this->render('admin/back_office_category.html.twig', [
        'category' => $category,
        'categories' => $categories

        ]);
    } // end function showBackofficeCategory() 
}