<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Framework;

use App\Infrastructure\Framework\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class TestClassController extends AbstractController
{
    public function successResponse(): JsonResponse
    {
        return $this->success(
            data: ['key' => 'value'],
            message: 'success',
        );
    }

    public function failResponse(): JsonResponse
    {
        return $this->fail('fail');
    }
}
