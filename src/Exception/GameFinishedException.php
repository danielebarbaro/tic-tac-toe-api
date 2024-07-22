<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;

class GameFinishedException extends HttpException
{
    public function __construct(string $message = 'Game finished.', int $statusCode = 200, ?Exception $previous = null, array $headers = [])
    {
        parent::__construct($statusCode, $message, $previous, $headers);
    }
}
