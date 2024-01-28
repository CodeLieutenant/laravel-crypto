<?php

declare(strict_types=1);

namespace BrosSquad\LaravelCrypto\Exceptions;

use Exception;

class KeyPathNotFound extends Exception
{
    public function __construct(string $message = "Key path not found", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}