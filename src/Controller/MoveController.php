<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Move;
use App\Repository\MoveRepository;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', 'api_')]
#[OA\Tag(name: 'Moves')]
class MoveController extends AbstractController
{
    private MoveRepository $moveRepository;

    public function __construct(MoveRepository $moveRepository)
    {
        $this->moveRepository = $moveRepository;
    }

    #[OA\Get(
        path: '/games/{gameId}/moves',
        summary: 'Retrieve a list of moves',
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Returns a list of moves',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: Move::class))
                )
            ),
        ]
    )]
    #[Route('/games/{game}/moves', name: 'api_moves', methods: ['GET'])]
    public function index(Game $game): JsonResponse
    {
        $moves = $this->moveRepository->findAll([
            'game' => $game,
        ]);

        return $this->json(
            $moves,
            Response::HTTP_OK,
            [],
            ['groups' => 'move:read']
        );
    }

    #[OA\Get(
        path: '/games/{gameId}/moves/{id}',
        summary: 'Retrieve a single move by ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The ID of the move',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'uuid')
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Returns the move details',
                content: new OA\JsonContent(ref: new Model(type: Move::class))
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'Move not found'
            ),
        ]
    )]
    #[Route('/moves/{id}', name: 'api_move', methods: ['GET'])]
    public function show(Move $move): JsonResponse
    {
        return $this->json(
            $move,
            Response::HTTP_OK,
            [],
            ['groups' => 'move:read']
        );
    }
}
