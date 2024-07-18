<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class AddAddPlayerController extends AbstractController
{
    #[Route('/add/add/player', name: 'app_add_add_player')]
    public function __invoke(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AddAddPlayerController.php',
        ]);
    }
}
