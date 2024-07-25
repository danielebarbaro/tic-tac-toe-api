<?php

namespace App\Controller;

use App\Entity\Game;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', 'api_')]
#[OA\Tag(name: 'Games')]
class CreateGameController extends AbstractController
{
    private GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    #[OA\Post(
        path: '/games',
        summary: 'Create a new game',
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Game created successfully',
                content: new OA\JsonContent(
                    ref: new Model(type: Game::class, groups: ['game']),
                    type: 'object'
                )
            ),
            new OA\Response(
                response: Response::HTTP_PRECONDITION_FAILED,
                description: 'Invalid input'
            ),
        ]
    )]
    #[Route('/games', name: 'app_create_game', methods: ['POST'])]
    public function __invoke(): JsonResponse
    {
        $game = new Game(2);
        $this->gameRepository->save($game);

        return $this->json(
            $game,
            Response::HTTP_CREATED,
            [],
            ['groups' => 'game:read']
        );
    }
}
