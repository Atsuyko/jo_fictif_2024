<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/commande/création', name: 'order')]
    #[IsGranted('ROLE_USER')]
    /**
     * Create a new order before redirect to payement
     *
     * @param User $user
     * @param EntityManagerInterface $emi
     * @param TicketRepository $ticketRepository
     * @param SessionInterface $si
     * @return Response
     */
    public function create(EntityManagerInterface $emi, TicketRepository $ticketRepository, SessionInterface $si): Response
    {
        $user = $this->getUser();

        $cart = $si->get('cart', []);
        $totalPrice = 0;


        foreach ($cart as $id) {
            $ticket = $ticketRepository->find($id);
            $totalPrice += $ticket->getPrice();
        }

        $order = new Order();
        $order->setUser($user)
            ->setPrice($totalPrice);

        $emi->persist($order);
        $emi->flush($order);

        foreach ($cart as $id) {
            $ticket = $ticketRepository->find($id);
            $ticket->setOrder($order);

            $emi->persist($ticket);
            $emi->flush($ticket);
        }

        /* Redirect to PaymentController for payement */
        return $this->redirectToRoute('payement', ['id' => $order->getId()]);
    }
}
