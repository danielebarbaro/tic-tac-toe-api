<?php

namespace App\EventListener;

// src/EventListener/ExceptionListener.php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
    public function __construct(private bool $debug = false) {}

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        $error = [
            'message' => $this->debug ? $exception->getMessage() : "General error.",
            'code' => $exception->getStatusCode(),
        ];

        if ($this->debug) {
            $error['trace'] = $exception->getTrace();
        }

        $response = new JsonResponse($error, $statusCode);

        $event->setResponse($response);
    }
}