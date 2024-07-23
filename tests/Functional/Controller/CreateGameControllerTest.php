<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateGameControllerTest extends WebTestCase
{
    public function testCreateGame(): void
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/games');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseFormatSame('json');
    }
}
