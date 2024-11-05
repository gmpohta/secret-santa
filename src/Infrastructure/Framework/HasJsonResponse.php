<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait HasJsonResponse
{
    /**
     * @param array<mixed> $data
     */
    protected function success(array $data = [], ?string $message = null, int $code = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $code);
    }

    /**
     * @param array<mixed> $data
     */
    protected function fail(string $message, array $data = [], int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return new JsonResponse([
            'success' => false,
            'data' => $data,
            'message' => $message,
        ], $code);
    }
}
