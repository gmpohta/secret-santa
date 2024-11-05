<?php

declare(strict_types=1);

namespace App\Api\ClearUsers;

use App\Infrastructure\Framework\AbstractController;
use App\Usecase\Services\ClearUsersService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class Action extends AbstractController
{
    public function __construct(
        private readonly ClearUsersService $clearUsersService,
    ) {}

    #[Route(path: '/api/v1/clear-users', name: 'api_v1_clear_users', methods: ['DELETE'])]
    public function __invoke(): JsonResponse
    {

        ($this->clearUsersService)();

        return $this->success();
    }
}
