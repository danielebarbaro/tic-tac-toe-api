<?php

namespace App\Tests\Functional\Controller;

use App\Dto\MoveCreateDto;
use App\Enum\GamePlayerEnum;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateMoveControllerTest extends WebTestCase
{
    public function testCreateMove(): void
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
    }

    public function testWrongMove(): void
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
        $moveCreateDto->position = 2;
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

        $client->request(
            'POST',
            "/api/games/{$game}/moves",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($moveCreateDto)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertResponseFormatSame('json');
    }

    public function testWrongMovePosition(): void
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
        $moveCreateDto->position = 2;
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

        $moveCreateDto = new MoveCreateDto();
        $moveCreateDto->player = GamePlayerEnum::PLAYER_TWO;
        $moveCreateDto->position = 2;

        $client->request(
            'POST',
            "/api/games/{$game}/moves",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($moveCreateDto)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertResponseFormatSame('json');
    }
}
