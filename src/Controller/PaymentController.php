<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    private EntityManagerInterface $emi;
    private UrlGeneratorInterface $ugi;

    public function __construct(EntityManagerInterface $emi, UrlGeneratorInterface $ugi)
    {
        $this->emi = $emi;
        $this->ugi = $ugi;
    }

    #[Route('/create-session-stripe/{id}', name: 'payement')]
    public function stripeCheckout($id): RedirectResponse
    {
        $orderStripe = [];

        $order = $this->emi->getRepository(Order::class)->find($id);

        if (!$order) {
            return $this->redirectToRoute('cart_index');
        }

        foreach ($order->getTickets() as $ticket) {
            $orderStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $ticket->getPrice() * 100,
                    'product_data' => [
                        'name' => 'Ticket'
                    ]
                ],
                'quantity' => 1,
            ];
        }

        Stripe::setApiKey($_ENV['STRIPE_SECRET']);

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getUserIdentifier(),
            'payment_method_types' => ['card'],
            'line_items' => [[
                $orderStripe
            ]],
            'mode' => 'payment',
            'success_url' => $this->ugi->generate('payment_success', ['id' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->ugi->generate('payment_cancel', ['id' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return new RedirectResponse($checkout_session->url);
    }

    #[Route('/commande/succes/{id}', name: 'payment_success')]
    public function stripeSuccess(Order $order): RedirectResponse
    {
        $order->setUpdatedAt(new \DateTimeImmutable())
            ->setPaidAt(new \DateTimeImmutable())
            ->setPaid(true);

        $tickets = $order->getTickets();

        foreach ($tickets as $ticket) {
            $ticket->setQrkey($order->getId()->toString() . $ticket->getId()->toString())
                ->setPaid(true);

            $this->emi->persist($ticket);
            $this->emi->flush($ticket);
        }

        $this->emi->persist($order);
        $this->emi->flush($order);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/commande/succes/{id}', name: 'payment_cancel')]
    public function stripeCancel(Order $order): RedirectResponse
    {
        return $this->redirectToRoute('cart_index');
    }
}