<?php

namespace App\Controller;

use App\Entity\Project;
use App\Enum\TaskCategoryEnum;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\TaskRepository;

#[Route('/')]
#[Route('/project')]
final class ProjectController extends AbstractController
{
    #[Route(name: 'app_project_index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('project/projects.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }

    #[Route('/{id}/show', name: 'app_project_show', methods: ['GET'])]
    public function show(Project $project, TaskRepository $taskRepository): Response
    {
        $categories = [];
        foreach (TaskCategoryEnum::cases() as $categoryName) {
            $tasksInCategory = $taskRepository->findAllByCategoryProject($categoryName->value, $project);
            $categories[] = [
                'name' => $categoryName->value,
                'tasks' => $tasksInCategory
            ];
        }
        return $this->render('project/show.html.twig', [
            'project' => $project,
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'app_project_new', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'app_project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ?Project $project = null, EntityManagerInterface $entityManager): Response
    {
        if ($project === null) {
            $project = new Project();
        }
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_show', [
                'id' => $project->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_project_delete', methods: ['POST'])]
    public function delete(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            try {
                $entityManager->remove($project);
                $entityManager->flush();

                $this->addFlash('success', 'Projet supprimé.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur pendant la suppression du projet : ' . $e->getMessage());
            }
        }

        return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
    }
}
