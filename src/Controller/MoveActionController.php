<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class MoveActionController extends AbstractController
{
    #[Route('/move/action', name: 'app_move_action')]
    public function __invoke(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/MoveActionController.php',
        ]);
    }
}
