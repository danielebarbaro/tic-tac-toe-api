<?php

namespace App\Controller;

use App\Entity\Game;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/api', 'api_')]
#[OA\Tag(name: 'Games')]
class GameController extends AbstractController
{
    private GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    #[OA\Get(
        path: '/games',
        summary: 'Retrieve a list of games',
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Returns a list of games',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: Game::class, groups: ['game:read']))
                )
            ),
        ]
    )]
    #[Route('/games', name: 'api_games', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $games = $this->gameRepository->findAll();

        return $this->json(
            $games,
            Response::HTTP_OK,
            [],
            ['groups' => 'game:read']
        );
    }

    #[OA\Get(
        path: '/games/{id}',
        summary: 'Retrieve a single game by ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The ID of the game',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'uuid')
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Returns the game details',
                content: new OA\JsonContent(ref: new Model(type: Game::class, groups: ['game:read']))
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'Game not found'
            ),
        ]
    )]
    #[Route('/games/{id}', name: 'api_game', methods: ['GET'])]
    public function show(Game $game): JsonResponse
    {
        return $this->json(
            $game,
            Response::HTTP_OK,
            [],
            ['groups' => 'game:read']
        );
    }
}
