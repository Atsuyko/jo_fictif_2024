<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/espace-utilisateur/{id}', name: 'user_space')]
    public function userSpace(User $user, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('home');
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

                return $this->redirectToRoute('user_space', ['id' => $user->getId()]);
            } else {
                $this->addFlash(
                    'danger',
                    'Votre mot de passe est incorrect.'
                );
            }
        }

        return $this->render('user/space.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
