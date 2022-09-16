<?php

namespace App\Controller;

use DateTime;
use App\Entity\Membre;
use App\Entity\Contact;
use App\Entity\Commande;
use App\Form\UpdateMembreFormType;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/tableau-de-bord', name: 'show_dashboard', methods: ['GET'])]
    public function showDashboard(EntityManagerInterface $entityManager): Response
    {
        $membres = $entityManager->getRepository(Membre::class)->findBy([
            "deletedAt" => null,
        ]);

        return $this->render('admin/show_dashboard.html.twig', [
            'membres' => $membres,
        ]);
    }

    #[Route('/voir-les-archives', name: 'show_archives', methods: ['GET'])]
    public function showArchives(EntityManagerInterface $entityManager): Response
    {
        $membres = $entityManager->getRepository(Membre::class)->findAllArchived();

        return $this->render('admin/show_archives.html.twig', [
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

    #[Route("/archiver-un-membre/{id}", name: 'soft_delete_membre', methods: ['GET'])]
    public function softDeleteMembre(Membre $membre, MembreRepository $repository): RedirectResponse
    {
        $membre->setDeletedAt(new DateTime());

        $repository->add($membre, true);

        $this->addFlash('success', 'Le membre a bien été archivé. Voir les archives !');

        return $this->redirectToRoute('show_dashboard');
        
    }

    #[Route("/restaurer-un-membre/{id}", name: 'restore_membre', methods: ['GET'])]
    public function restoreMembre(Membre $membre, MembreRepository $repository): Response
    {
        $membre->setDeletedAt(null);

        $repository->add($membre, true);

        $this->addFlash('success', 'Le membre a bien été restaurer !');

        return $this->redirectToRoute('show_dashboard');
        
    }

    #[Route("/supprimer-un-membre/{id}", name: 'hard_delete_membre', methods: ['GET'])]
    public function hardDeleteMembre(Membre $membre, MembreRepository $repository): Response
    {
        $repository->remove($membre, true);

        $this->addFlash('success', 'Le membre a bien été supprimer !');

        return $this->redirectToRoute('show_dashboard');
        
    }

    #[Route('/voir-contact', name: 'show_contact', methods: ['GET'])]
    public function showContact(EntityManagerInterface $entityManager): Response
    {
        $contacts = $entityManager->getRepository(Contact::class)->findBy([
            "deletedAt" => null,
        ]);

        return $this->render('admin/show_contact.html.twig', [
            'contacts' => $contacts,
        ]);
    }
    
    #[Route('/admin/voir-les-commandes', name: 'show_commandes', methods: ['GET'])]
    public function showCommandes(EntityManagerInterface $entityManager): Response
    {
        $commandes = $entityManager->getRepository(Commande::class)->findBy(['deletedAt' => null]);

        return $this->render('admin/show_commandes.html.twig', [
            'commandes' => $commandes,
        ]);
    }


}
