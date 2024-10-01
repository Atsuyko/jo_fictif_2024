<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NoticeController extends AbstractController
{
    #[Route('/mentions-légales', name: 'notice')]
    public function index(): Response
    {
        return $this->render('notice/index.html.twig', []);
    }
}
