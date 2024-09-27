<?php

namespace App\EventListener;

use App\Exception\GameFinishedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionListener
{
    public function __construct(private bool $debug = false)
    {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $message = 'An error occurred.';
        $errors = [];

        switch ($exception) {
            case $exception instanceof GameFinishedException:
            case $exception instanceof HttpExceptionInterface:
                $statusCode = $exception->getStatusCode() ?? Response::HTTP_NOT_FOUND;
                $message = $exception->getMessage();
                break;
            case $exception instanceof ValidationFailedException:
                $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
                $message = 'Validation failed.';

                foreach ($exception->getViolations() as $violation) {
                    $errors[] = [
                        'propertyPath' => $violation->getPropertyPath(),
                        'message' => $violation->getMessage(),
                    ];
                }
                break;
        }

        $error = [
            'message' => $this->debug ? $message : 'General error.',
            'errors' => $errors,
            'code' => $statusCode,
        ];

        if ($this->debug) {
            $error['trace'] = $exception->getTrace();
        }

        $response = new JsonResponse($error, $statusCode);

        $event->setResponse($response);
    }
}
