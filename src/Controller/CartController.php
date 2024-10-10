<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/panier', name: 'cart_')]
class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $si, TicketRepository $ticketRepository): Response
    {
        $cart = $si->get('cart', []);

        $tickets = [];
        $totalPrice = 0;


        foreach ($cart as $id) {
            $ticket = $ticketRepository->find($id);

            if ($ticket->getOrder() !== null && $ticket->getOrder()->isPaid() === true) {
                unset($cart[array_search($ticket->getId()->toString(), $cart)]);
                $si->set('cart', $cart);
            } else {
                array_push($tickets, $ticket);

                $totalPrice += $ticket->getPrice();
            }
        }

        return $this->render('cart/index.html.twig', [
            'tickets' => $tickets,
            'totalPrice' => $totalPrice,
        ]);
    }

    #[Route('/add/{id}', name: 'add')]
    public function add(Ticket $ticket, SessionInterface $si)
    {
        $cart = $si->get('cart', []);
        $id = $ticket->getId()->toString();

        if (!isset($cart[$id])) {
            array_push($cart, $id);
        }

        $si->set('cart', $cart);

        return $this->redirectToRoute('cart_index');
    }
}
