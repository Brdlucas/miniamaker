<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/profile', name: 'app_profile', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/complete', name: 'app_complete', methods: ['GET', 'POST'])]
    public function complete(): Response
    {
        //    Redirection avec flash message
        $this->addFlash('success', 'Votre profil est complété');
        return $this->render('app_profile');
    }
}