<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Entity\Listing;
use App\Form\ListingType;
use App\Repository\TaskRepository;
use App\Repository\ListingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    // Formulaire création nouvelle LISTE
    #[Route('/newList', name: 'app_new_list')]
    public function NewListing(Request $request, EntityManagerInterface $entityManager): Response
    {
        $listing = new Listing();
        $formNewList = $this->createForm(ListingType::class, $listing);
        $formNewList->handleRequest($request);

        if ($formNewList->isSubmitted() && $formNewList->isValid()) {
            $entityManager->persist($listing);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('list/createList.html.twig', [
            'formNewList' => $formNewList->createView()
        ]);
    }

    //  Formulaire création nouvelle TÂCHE
    #[Route('/newTask/{id}', name: 'app_new_task')]
    public function NewTask(Request $request, EntityManagerInterface $entityManager, ListingRepository $listingRepository, $id): Response
    {
        // récup id liste en cours
        $list = $listingRepository->findOneBy(['id' => $id]);
        // dd($list);
        $list_id = $list->getId('id');
        // dd($list_id);
        // instance nouvelle tâche
        $task = new Task();       
        // créa du form
        $formNewTask = $this->createForm(TaskType::class, $task);
        $formNewTask->handleRequest($request);    

        // validation du form
        if ($formNewTask->isSubmitted() && $formNewTask->isValid()) {
            // dd($task);
          
            $entityManager->persist($task);
            $entityManager->flush();

           

            return $this->redirectToRoute('app_show_taks');
        }

        return $this->render('task/createTask.html.twig', [
            'formNewTask' => $formNewTask->createView(),
            'list' => $list,
            'list_id' => $list_id,
            // 'showList' => $showList,

        ]);
    }

    // Page index = affiche TOUTES les LISTES
    #[Route('/', name: 'app_home')]
    public function show_lists(ListingRepository $ViewList): Response
    {
        $showLists =  $ViewList->findAll();
        // dd($showLists);
    
       


        return $this->render('index.html.twig', [
            'showLists' => $showLists,
        ]);
    }

    // Lien via id d'une liste => détails (tâches attribuées)
    #[Route('/liste/{id}', name: 'liste')]
    public function list($id, TaskRepository $taskRepository, Request $request, ListingRepository $listingRepository): Response
    {
        // lien avec taks qui ont dans list_id le même id de la liste select
        $tasks = $taskRepository->findBy(['List' => $id]);
        $list = $listingRepository->findOneBy(['id' => $id]);
        // dd($list);
        // je recupère les taches en rapport avec la bonne liste 
    

        return $this->render('list/detailsList.html.twig', [
            'tasks' => $tasks,
            'list' => $list
        ]);
    }

    // Affichage TOUTES TÂCHES
    #[Route('showTasks', name: 'app_show_taks')]
    public function show_tasks(TaskRepository $ViewTask): Response
    {
        $showTasks =  $ViewTask->findAll();
        // dd($showTasks);

        return $this->render('task/viewTasks.html.twig', [
            'showTasks' => $showTasks,
        ]);
    }
}
