<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Offer;
use App\Entity\Ticket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TicketController extends AbstractController
{
    #[Route('/ticket/create{event}{offer}', name: 'ticket')]
    /**
     * Create a new ticket and redirect for add in cart
     *
     * @param Event $event
     * @param Offer $offer
     * @param EntityManagerInterface $emi
     * @return Response
     */
    public function create(Event $event, Offer $offer, EntityManagerInterface $emi): Response
    {
        $ticket = new Ticket();
        $ticket->setEvent($event)
            ->setOffer($offer);

        $emi->persist($ticket);
        $emi->flush($ticket);

        /* Redirect to CartController for add in cart */
        return $this->redirectToRoute('cart_add', ['id' => $ticket->getId()]);
    }

    #[Route('/ticket/delete/{id}', name: 'ticket_delete')]
    /**
     * Delete a ticket from the cart
     *
     * @param Ticket $ticket
     * @param EntityManagerInterface $emi
     * @param SessionInterface $si
     * @return Response
     */
    public function delete(Ticket $ticket, EntityManagerInterface $emi, SessionInterface $si): Response
    {
        /* Remove ticket from cart */
        $cart = $si->get('cart', []);
        unset($cart[array_search($ticket->getId()->toString(), $cart)]);
        $si->set('cart', $cart);

        $emi->remove($ticket);
        $emi->flush($ticket);



        $this->addFlash(
            'success',
            'Le ticket à bien été supprimé.'
        );

        return $this->redirectToRoute('cart_index');
    }
}
