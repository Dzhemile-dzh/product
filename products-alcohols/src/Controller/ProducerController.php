<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProducerController extends AbstractController
{
    #[Route('/producer', name: 'app_producer')]
    public function index(): Response
    {
        return $this->render('producer/index.html.twig', [
            'controller_name' => 'ProducerController',
        ]);
    }
}
