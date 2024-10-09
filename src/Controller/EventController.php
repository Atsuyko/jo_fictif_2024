<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    #[Route('/évènement/18689{id}965236', name: 'event')]
    /**
     * Display event which user click on
     *
     * @param Event $event
     * @return Response
     */
    public function index(Event $event): Response
    {
        $offers = $event->getOffers();

        return $this->render('event/index.html.twig', [
            'offers' => $offers,
            'event' => $event,
        ]);
    }
}
