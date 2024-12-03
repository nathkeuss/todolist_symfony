<?php

namespace App\Controller;

use App\Entity\Priority;
use App\Form\PriorityType;
use App\Repository\PriorityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// PriorityController est une classe contrôleur qui gère les routes liées à l'entité Priority.
class PriorityController extends AbstractController
{
    // Route pour afficher la liste des priorités
    #[Route('/priority', name: 'app_priority')]
    public function index(PriorityRepository $priorityRepository): Response
    {
        // Récupère toutes les priorités à partir du repository
        $priorities = $priorityRepository->findAll();

        // Rend la vue Twig avec les priorités récupérées
        return $this->render('priority/index.html.twig', [
            'priorities' => $priorities,
        ]);
    }

    // Route pour créer une nouvelle priorité
    #[Route('/priority/create', name: 'create_priority')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        // Crée une nouvelle instance de l'entité Priority
        $priority = new Priority();

        // Crée un formulaire basé sur la classe PriorityType et lie l'objet Priority
        $form = $this->createForm(PriorityType::class, $priority);

        // Gère la requête HTTP (par ex., POST) pour peupler le formulaire
        $form->handleRequest($request);

        // Vérifie si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Persiste l'objet Priority dans la base de données
            $entityManager->persist($priority);
            $entityManager->flush();

            // Redirige l'utilisateur vers la page listant les priorités
            return $this->redirectToRoute('app_priority');
        }

        // Prépare la vue du formulaire pour l'envoyer à Twig
        $formView = $form->createView();

        // Rend la vue Twig pour la création avec le formulaire
        return $this->render('priority/create.html.twig', compact('formView'));
    }
}

