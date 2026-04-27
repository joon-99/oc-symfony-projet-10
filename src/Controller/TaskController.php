<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Task;
use App\Entity\Project;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    #[Route('/new/{project}', name: 'app_task_new', methods: ['GET', 'POST'])]
    #[Route('/task/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function taskEdit(?Task $task, ?Project $project = null,Request $request, EntityManagerInterface $entityManager): Response {
        if (!$task) {
            $task = new Task();
            if ($project) {
                $task->setProject($project);
            } else {
                throw new BadRequestException('Une tâche doit appartenir à un projet.');
            }
        }
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute('app_project_show', ['id' => $task->getProject()->getId()]);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            try {
                $entityManager->remove($task);
                $entityManager->flush();

                $this->addFlash('success', 'Tâche supprimée.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur pendant la suppression de la tâche : ' . $e->getMessage());
            }
        }

        return $this->redirectToRoute('app_project_show', ['id' => $task->getProject()->getId()], Response::HTTP_SEE_OTHER);
    }
}