<?php

namespace App\Controller;

use App\Entity\Serveur;
use App\Form\AddEditServeurType;
use App\Repository\ServeurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServeurController extends AbstractController
{
    #[Route('/serveur', name: 'app_serveur')]
    public function index(): Response
    {
        return $this->render('serveur/index.html.twig', [
            'controller_name' => 'ServeurController',
        ]);
    }

    #[Route('/serveur/list', name: 'app_serveur_list')]
    public function serveursList(ServeurRepository $serveurRepository): Response
    {
        $serveursDB = $serveurRepository->findAll();
        return $this->render('serveur/list.html.twig', [
            'serveurs' => $serveursDB,
        ]);
    }

    #[Route('/serveur/new', name: 'app_serveur_new')]
    public function newServeur(Request $request, EntityManagerInterface $em): Response
    {
        $serveur = new Serveur();
        $form = $this->createForm(AddEditServeurType::class, $serveur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($serveur);
            $em->flush();
            return $this->redirectToRoute('app_serveur_list');
        }
        return $this->render('serveur/form.html.twig', [
            'title' => 'Add serveur',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/serveur/edit/{id}', name: 'app_serveur_edit')]
    public function editServeur(int $id, ServeurRepository $serveurRepository, Request $request, EntityManagerInterface $em): Response
    {
        $serveur = $serveurRepository->find($id);
        if (!$serveur) {
            throw $this->createNotFoundException('Serveur not found');
        }
        $form = $this->createForm(AddEditServeurType::class, $serveur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_serveur_list');
        }
        return $this->render('serveur/form.html.twig', [
            'title' => 'Update serveur',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/serveur/delete/{id}', name: 'app_serveur_delete')]
    public function deleteServeur(int $id, ServeurRepository $serveurRepository, EntityManagerInterface $em): Response
    {
        $serveur = $serveurRepository->find($id);
        if ($serveur) {
            $em->remove($serveur);
            $em->flush();
        }
        return $this->redirectToRoute('app_serveur_list');
    }

    #[Route('/serveur/details/{id}', name: 'app_serveur_details')]
    public function serveurDetails(int $id, ServeurRepository $serveurRepository): Response
    {
        $serveur = $serveurRepository->find($id);
        if (!$serveur) {
            throw $this->createNotFoundException('Serveur not found');
        }
        return $this->render('serveur/details.html.twig', [
            'serveur' => $serveur,
        ]);
    }
}
