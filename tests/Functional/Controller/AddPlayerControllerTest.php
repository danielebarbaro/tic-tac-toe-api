<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Dto\MoveCreateDto;
use App\Dto\PlayerUpdateDto;
use App\Enum\GamePlayerEnum;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AddPlayerControllerTest extends WebTestCase
{
    public function testAddPlayerToNewGame(): void
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/games');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseFormatSame('json');
        $response = json_decode($client->getResponse()->getContent(), true);

        $game = $response['id'];

        $playerUpdateDto = new PlayerUpdateDto();
        $playerUpdateDto->players = 1;

        $crawler = $client->request(
            'PATCH',
            "/api/games/{$game}/players",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($playerUpdateDto)
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseFormatSame('json');
    }

    public function testAddPlayerToStartedGame(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/games');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseFormatSame('json');

        $response = json_decode($client->getResponse()->getContent(), true);

        $game = $response['id'];

        $moveCreateDto = new MoveCreateDto();
        $moveCreateDto->player = GamePlayerEnum::PLAYER_ONE;
        $moveCreateDto->position = 1;
        $client->request(
            'POST',
            "/api/games/{$game}/moves",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($moveCreateDto)
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseFormatSame('json');

        $client->request('GET', "/api/games/{$game}/moves");

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseFormatSame('json');

        $playerUpdateDto = new PlayerUpdateDto();
        $playerUpdateDto->players = 1;

        $crawler = $client->request(
            'PATCH',
            "/api/games/{$game}/players",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($playerUpdateDto)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertResponseFormatSame('json');
    }
}
