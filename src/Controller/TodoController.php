<?php

namespace App\Controller;

use App\Form\TodoType;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'app_todo')]
    public function index(TodoRepository $todoRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Form
        $formTodo = $this->createForm(TodoType::class);

        $formTodo->handleRequest($request);

        if ($formTodo->isSubmitted() && $formTodo->isValid()) {
            $task = $formTodo->getData();

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_todo');
        }

        // Prepare view data
        $todosList = $this->getTodos($todoRepository);

        return $this->render('todo/index.html.twig', [
            'form' => $formTodo->createView(),
            'todos' => $todosList['todos'],
            'dones' => $todosList['dones'],
        ]);
    }

    #[Route('/todo/editer/{id}', name: 'app_todo_edit')]
    public function edit(?int $id, TodoRepository $todoRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Form
        $task = $todoRepository->find($id);

        if (!$task) {
            return $this->redirectToRoute('app_todo');
        }

        $formTodo = $this->createForm(TodoType::class, $task);

        $formTodo->handleRequest($request);

        if ($formTodo->isSubmitted() && $formTodo->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_todo');
        }


        // Prepare view data
        $todosList = $this->getTodos($todoRepository);

        return $this->render('todo/index.html.twig', [
            'form' => $formTodo->createView(),
            'todos' => $todosList['todos'],
            'dones' => $todosList['dones'],
        ]);
    }

    #[Route('/todo/supprimer/{id}', name: 'app_todo_delete')]
    public function delete(?int $id, TodoRepository $todoRepository, EntityManagerInterface $entityManager): Response
    {
        $task = $todoRepository->find($id);

        if ($task) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_todo');
    }

    #[Route('/todo/terminer/{id}', name: 'app_todo_end')]
    public function end(?int $id, TodoRepository $todoRepository, EntityManagerInterface $entityManager): Response
    {
        $task = $todoRepository->find($id);

        if ($task) {
            $task->setEndedAt(new \DateTime());
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_todo');
    }

    #[Route('/todo/annuler/{id}', name: 'app_todo_cancel')]
    public function cancel(?int $id, TodoRepository $todoRepository, EntityManagerInterface $entityManager): Response
    {
        $task = $todoRepository->find($id);

        if ($task) {
            $task->setEndedAt(null);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_todo');
    }

    private function getTodos(TodoRepository $todoRepository): array
    {
        $todos = $todoRepository->createQueryBuilder('t')
            ->where('t.endedAt IS NULL')
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    
        $dones = $todoRepository->createQueryBuilder('t')
            ->where('t.endedAt IS NOT NULL')
            ->orderBy('t.endedAt', 'DESC')
            ->getQuery()
            ->getResult();

        return ['todos' => $todos, 'dones' => $dones];
    }
}
