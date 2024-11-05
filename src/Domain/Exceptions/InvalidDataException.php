<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

final class InvalidDataException extends AbstractAppException
{
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, 400, $previous);
    }
}
