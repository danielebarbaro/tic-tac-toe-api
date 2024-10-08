<?php

namespace App\Tests\Functional;

use App\Dto\MoveCreateDto;
use App\Enum\GamePlayerEnum;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PlayMatchTest extends WebTestCase
{
    public function testPlayScenarios(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/games');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseFormatSame('json');

        $response = json_decode($client->getResponse()->getContent(), true);

        $gameId = $response['id'];

        $moves = [
            [
                'player' => GamePlayerEnum::PLAYER_ONE,
                'position' => 1,
            ],
            [
                'player' => GamePlayerEnum::PLAYER_TWO,
                'position' => 5,
            ],
            [
                'player' => GamePlayerEnum::PLAYER_ONE,
                'position' => 2,
            ],
            [
                'player' => GamePlayerEnum::PLAYER_TWO,
                'position' => 3,
            ],
            [
                'player' => GamePlayerEnum::PLAYER_ONE,
                'position' => 7,
            ],
            [
                'player' => GamePlayerEnum::PLAYER_TWO,
                'position' => 4,
            ],
            [
                'player' => GamePlayerEnum::PLAYER_ONE,
                'position' => 8,
            ],
            [
                'player' => GamePlayerEnum::PLAYER_TWO,
                'position' => 6,
            ],
        ];

        foreach ($moves as $move) {
            $moveCreateDto = new MoveCreateDto();
            $moveCreateDto->player = $move['player'];
            $moveCreateDto->position = $move['position'];

            $client->request(
                'POST',
                "/api/games/{$gameId}/moves",
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                json_encode($moveCreateDto)
            );

            $this->assertResponseIsSuccessful();
            $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
            $this->assertResponseFormatSame('json');
            $moveResponse = json_decode($client->getResponse()->getContent(), true);
        }

        $this->assertEquals(2, $moveResponse['winner']);
    }

    public function testPlayTieScenarios(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/games');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseFormatSame('json');

        $response = json_decode($client->getResponse()->getContent(), true);

        $gameId = $response['id'];
        // X | O | X
        // X | X | O
        // O | X | O
        $moves = [
            [
                'player' => GamePlayerEnum::PLAYER_ONE,
                'position' => 1,
            ],
            [
                'player' => GamePlayerEnum::PLAYER_TWO,
                'position' => 2,
            ],
            [
                'player' => GamePlayerEnum::PLAYER_ONE,
                'position' => 3,
            ],
            [
                'player' => GamePlayerEnum::PLAYER_TWO,
                'position' => 6,
            ],
            [
                'player' => GamePlayerEnum::PLAYER_ONE,
                'position' => 5,
            ],
            [
                'player' => GamePlayerEnum::PLAYER_TWO,
                'position' => 7,
            ],
            [
                'player' => GamePlayerEnum::PLAYER_ONE,
                'position' => 4,
            ],
            [
                'player' => GamePlayerEnum::PLAYER_TWO,
                'position' => 9,
            ],
            [
                'player' => GamePlayerEnum::PLAYER_ONE,
                'position' => 8,
            ],
        ];

        foreach ($moves as $move) {
            $moveCreateDto = new MoveCreateDto();
            $moveCreateDto->player = $move['player'];
            $moveCreateDto->position = $move['position'];

            $client->request(
                'POST',
                "/api/games/{$gameId}/moves",
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                json_encode($moveCreateDto)
            );

            $this->assertResponseIsSuccessful();
            $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
            $this->assertResponseFormatSame('json');
            $moveResponse = json_decode($client->getResponse()->getContent(), true);
        }

        $this->assertEquals(null, $moveResponse['winner']);

        $client->request('GET', '/api/games/'.$gameId);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseFormatSame('json');

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('TIE', $response['status']);
    }
}
