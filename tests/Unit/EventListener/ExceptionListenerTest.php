<?php

namespace App\Tests\Unit\EventListener;

use App\EventListener\ExceptionListener;
use App\Exception\GameFinishedException;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionListenerTest extends WebTestCase
{
    private function createExceptionEvent(\Throwable $exception): ExceptionEvent
    {
        $kernel = $this->createMock(HttpKernelInterface::class);
        $request = new Request();

        return new ExceptionEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST, $exception);
    }

    public function testGameFinishedException(): void
    {
        $exception = new GameFinishedException('Game is finished.', Response::HTTP_BAD_REQUEST);
        $event = $this->createExceptionEvent($exception);

        $listener = new ExceptionListener(false);
        $listener($event);

        $response = $event->getResponse();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode([
            'message' => 'General error.',
            'errors' => [],
            'code' => Response::HTTP_BAD_REQUEST,
        ]), $response->getContent());
    }

    public function testNotFoundHttpException(): void
    {
        $exception = new NotFoundHttpException('Not found');
        $event = $this->createExceptionEvent($exception);

        $listener = new ExceptionListener(false);
        $listener($event);

        $response = $event->getResponse();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode([
            'message' => 'General error.',
            'errors' => [],
            'code' => Response::HTTP_NOT_FOUND,
        ]), $response->getContent());
    }
}
