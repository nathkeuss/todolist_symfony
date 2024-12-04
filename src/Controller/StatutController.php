<?php

namespace App\Controller;

use App\Entity\Statut;
use App\Form\StatutType;
use App\Repository\StatutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatutController extends AbstractController
{
    #[Route('/statut', name: 'app_statut')]
    public function index(StatutRepository $statutRepository): Response
    {
        $status = $statutRepository->findAll();

        return $this->render('statut/index.html.twig', [
            'status' => $status,
        ]);
    }

    #[Route('/statut/create', name: 'create_statut')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $statut = new Statut();
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager->persist($statut);
            $entityManager->flush();

            return $this->redirectToRoute('app_statut');
        }

        $formView = $form->createView();

        return $this->render('statut/create_statut.html.twig', compact('formView'));
    }
}
