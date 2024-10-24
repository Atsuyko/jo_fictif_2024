<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\User;
use App\Form\UserPasswordType;
use App\Service\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/espace-utilisateur/{firstname}-{lastname}/{id}', name: 'user_space')]
    public function userSpace(User $user, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('home');
        }

        $orders = $user->getOrders();

        $ticketPaid = [];

        foreach ($orders as $order) {
            if ($order->isPaid()) {
                $tickets = $order->getTickets();

                foreach ($tickets as $ticket) {
                    array_push($ticketPaid, $ticket);
                }
                $tickets = $order->getTickets();
            }
        }

        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($userPasswordHasher->isPasswordValid($user, $form->getData()['password'])) {
                $user->setPlainPassword($form->getData()['plainPassword']);

                $em->persist($user);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe à bien été modifié.'
                );

                return $this->redirectToRoute('user_space', [
                    'id' => $user->getId(),
                    'firstname' => $user->getFirstname(),
                    'lastname' => $user->getLastname()
                ]);
            } else {
                $this->addFlash(
                    'danger',
                    'Votre mot de passe est incorrect.'
                );
            }
        }

        return $this->render('user/space.html.twig', [
            'form' => $form->createView(),
            'tickets' => $ticketPaid,
        ]);
    }

    #[Route('/pdf/{id}', name: 'pdf')]
    public function generateTicketPdf(Ticket $ticket, PdfService $pdf)
    {
        $html = $this->render('user/e-ticket.html.twig', ['ticket' => $ticket]);

        $pdf->generatePdfFile($html);
    }
}
