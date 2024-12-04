<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\ProjectType;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    #[Route('/project', name: 'app_project')]
    public function index(ProjectRepository $projectRepository): Response
    {
        // Récupère toutes les priorités à partir du repository
        $projects = $projectRepository->findAll();

        // Rend la vue Twig avec les priorités récupérées
        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/project/create', name: 'create_project')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $project->setCreatedAt(new \DateTime());

            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_project');
        }

        $formView = $form->createView();
        return $this->render('project/create_project.html.twig', compact('formView'));
    }

    #[Route('/project/{id}/update', name: 'update_project')]
    public function update(int $id, ProjectRepository $projectRepository, Request $request): Response {

        $project = $projectRepository->find($id);

        $project_form = $this->createForm(ProjectType::class, $project);

        $task = new Task();
        $task_form = $this->createForm(TaskType::class, $task);

        return $this->render('project/update_project.html.twig', [
            'project' => $project,
            'project_form' => $project_form->createView(),
            'task_form' => $task_form->createView(),
        ]);
    }


}
