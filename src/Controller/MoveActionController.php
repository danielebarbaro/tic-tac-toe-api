<?php

namespace App\Controller;

use App\Dto\MoveCreateDto;
use App\Entity\Game;
use App\Entity\Move;
use App\Repository\GameRepository;
use App\Repository\MoveRepository;
use App\Service\CheckMoveService;
use App\Service\GameStateMachineService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use HttpException;

#[Route('/api', 'api_')]
#[OA\Tag(name: 'Moves')]
class MoveActionController extends AbstractController
{
    private GameRepository $gameRepository;
    private GameStateMachineService $gameStateMachineService;
    private MoveRepository $moveRepository;
    private CheckMoveService $checkMoveService;

    public function __construct(
        CheckMoveService $checkMoveService,
        GameStateMachineService $gameStateMachineService,
        GameRepository $gameRepository,
        MoveRepository $moveRepository
    ) {
        $this->checkMoveService = $checkMoveService;
        $this->gameStateMachineService = $gameStateMachineService;
        $this->gameRepository = $gameRepository;
        $this->moveRepository = $moveRepository;
    }

    /**
     * @throws HttpException
     */
    #[OA\Post(
        path: '/games/{game}/moves',
        summary: 'A move in the game',
        requestBody: new OA\RequestBody(
            description: 'Details of the move',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'player',
                        description: 'The player making the move',
                        type: 'string'
                    ),
                    new OA\Property(
                        property: 'position',
                        description: 'The position of the move',
                        type: 'string'
                    ),
                ],
                type: 'object'
            )
        ),
        parameters: [
            new OA\Parameter(
                name: 'game',
                description: 'The ID of the game',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Move successfully made',
                content: new OA\JsonContent(
                    ref: new Model(type: Move::class, groups: ['move:read']),
                    type: 'object'
                )
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Invalid input'
            ),
            new OA\Response(
                response: Response::HTTP_UNPROCESSABLE_ENTITY,
                description: 'Validation failed'
            ),
        ]
    )]
    #[Route('/games/{game}/moves', name: 'app_move_action', methods: ['POST'])]
    public function __invoke(
        Game $game,
        #[MapRequestPayload]
        MoveCreateDto $moveCreateDto,
        ValidatorInterface $validator
    ): JsonResponse {
        $this->gameStateMachineService->execute($game);

        $move = new Move(
            $game,
            $moveCreateDto->position,
            $moveCreateDto->player
        );

        $errors = $validator->validate($move);
        if (count($errors) > 0) {
            throw new ValidationFailedException('moves', $errors);
        }

        $moveDto = $this->checkMoveService->execute($game, $move);

        return $this->json(
            $moveDto,
            Response::HTTP_CREATED,
            [],
            ['groups' => 'move:read']
        );
    }
}
