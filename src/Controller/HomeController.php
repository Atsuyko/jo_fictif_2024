<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    /**
     * Home page and display all event
     *
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();

        return $this->render('home/index.html.twig', [
            'events' => $events,
        ]);
    }
}
