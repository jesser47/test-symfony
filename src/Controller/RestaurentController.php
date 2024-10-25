<?php

namespace App\Controller;

use App\Entity\Restaurent;
use App\Form\AddEditRestaurentType;
use App\Repository\RestaurentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurentController extends AbstractController
{
    #[Route('/restaurent', name: 'app_restaurent')]
    public function index(): Response
    {
        return $this->render('restaurent/index.html.twig', [
            'controller_name' => 'RestaurentController',
        ]);
    }

    #[Route('/restaurent/list', name: 'app_restaurent_list')]
public function restaurentList(RestaurentRepository $restaurentRepository): Response
{
    $restaurentsDB = $restaurentRepository->findAll();
    
    return $this->render('restaurent/list.html.twig', [
        'restaurents' => $restaurentsDB, // Check that 'restaurents' is being passed
    ]);
}


    #[Route('/restaurent/new', name: 'app_restaurent_new')]
public function newAuthor(Request $request, EntityManagerInterface $em): Response
{
    $restaurent = new Restaurent();
    $form = $this->createForm(AddEditRestaurentType::class, $restaurent);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($restaurent);
        $em->flush();
        return $this->redirectToRoute('app_restaurent_list');
    }

    return $this->render('restaurent/form.html.twig', [
        'title' => 'Add Restaurant',
        'form' => $form,
    ]);
}



#[Route('/restaurent/details/{id}', name: 'app_restaurent_details')]
public function restaurentDetails(int $id, RestaurentRepository $restaurentRepository): Response
{
    $restaurent = $restaurentRepository->find($id);

    if (!$restaurent) {
        throw $this->createNotFoundException('The restaurant does not exist');
    }

    // Pass restaurent to the template
    return $this->render('restaurent/details.html.twig', [
        'restaurent' => ['nom' => 'Test Name', 'adresse' => 'Test Address'],
    ]);
    
}
#[Route('/restaurent/edit/{id}', name: 'app_restaurent_edit')]
public function editRestaurent(int $id, RestaurentRepository $restaurentRepository, Request $request, EntityManagerInterface $em): Response
{
    $restaurent = $restaurentRepository->find($id);
    if (!$restaurent) {
        throw $this->createNotFoundException('Restaurant not found');
    }

    $form = $this->createForm(AddEditRestaurentType::class, $restaurent);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        return $this->redirectToRoute('app_restaurent_list');
    }

    return $this->render('restaurent/form.html.twig', [
        'title' => 'Edit Restaurant',
        'form' => $form->createView(),
    ]);
}

#[Route('/restaurent/delete/{id}', name: 'app_restaurent_delete', methods: ['POST'])]
public function deleteRestaurent(int $id, RestaurentRepository $restaurentRepository, EntityManagerInterface $em): Response
{
    $restaurent = $restaurentRepository->find($id);
    if ($restaurent) {
        $em->remove($restaurent);
        $em->flush();
    }

    return $this->redirectToRoute('app_restaurent_list');
}



}
