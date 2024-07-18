<?php

namespace App\Controller;

use App\Entity\Game;
use App\Enum\GameStatusEnum;
use App\Repository\GameRepository;
use App\Validator\CheckPlayerUpdate;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route("/api", "api_")]
#[OA\Tag(name: 'Games')]
class AddPlayerController extends AbstractController
{
    private GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    #[OA\Patch(
        path: '/games/{id}/players',
        summary: 'Add a player to the game',
        requestBody: new OA\RequestBody(
            description: 'Data for adding a player',
            content: new OA\JsonContent(
                properties: [
                    'players' => new OA\Property(
                        description: 'The number of players to set',
                        type: 'integer'
                    ),
                ],
                type: 'object'
            )
        ),
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The ID of the game',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Player added successfully',
                content: new OA\JsonContent(
                    ref: new Model(type: Game::class, groups: ['game:read']),
                    type: 'object'
                )
            ),
            new OA\Response(
                response: Response::HTTP_NOT_FOUND,
                description: 'Game not found'
            ),
            new OA\Response(
                response: Response::HTTP_PRECONDITION_FAILED,
                description: 'Invalid input'
            ),
        ]
    )]
    #[Route('/games/{game}/players', name: 'api_game_add_player', methods: ['PATCH'])]
    public function __invoke(Game $game, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $requestBody = json_decode($request->getContent(), true);

        $players = $requestBody['players'];
        $game->setPlayers($players);
        $game->setStatus(GameStatusEnum::ONGOING);

        $errors = $validator->validate($game);
        if (count($errors) > 0) {
            throw new ValidationFailedException( 'games', $errors);
        }

        $this->gameRepository->save($game);

        return $this->json(
            $game,
            Response::HTTP_OK,
            [],
            ['groups' => 'game:read']
        );
    }
}
