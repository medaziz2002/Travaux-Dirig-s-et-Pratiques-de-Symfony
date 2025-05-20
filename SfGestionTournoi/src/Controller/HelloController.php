<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HelloController extends AbstractController{
    #[Route('/hello/michel', name: 'app_hello')]
    public function index(): Response
    {
        $text = 'Bonjour Michel';
        return $this->render('hello/addForm.html.twig', [
            'controller_name' => 'HelloController',
            'text' => $text
        ]);
    }
}
