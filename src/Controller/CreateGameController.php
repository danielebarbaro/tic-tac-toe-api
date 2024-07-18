<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CreateGameController extends AbstractController
{
    #[Route('/create/game', name: 'app_create_game')]
    public function __invoke(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CreateGameController.php',
        ]);
    }
}
