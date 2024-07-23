<?php

namespace App\Tests\Functional\Controller;

use App\Dto\MoveCreateDto;
use App\Enum\GamePlayerEnum;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class MoveControllerTest extends WebTestCase
{
    public function testGetMoves(): void
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
    }

    public function testMove(): void
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

        $response = json_decode($client->getResponse()->getContent(), true);

        $move = $response['id'];

        $crawler = $client->request('GET', "/api/moves/{$move}");

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseFormatSame('json');
    }
}
