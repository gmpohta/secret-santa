<?php

declare(strict_types=1);

namespace App\Api\AddUsers;

use App\Infrastructure\Framework\AbstractController;
use App\Usecase\Services\AddUsersService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class Action extends AbstractController
{
    public function __construct(
        private readonly AddUsersService $addUsersService,
    ) {}

    #[Route(path: '/api/v1/add-users', name: 'api_v1_add_users', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] Request $dto): JsonResponse
    {
        $errors = ($this->addUsersService)(
            $dto->users
        );

        if (0 < \count($errors)) {
            $errMessage = '';

            foreach ($errors as $error) {
                $errMessage .= '; ' . $error;
            }

            return $this->fail($errMessage);
        }

        return $this->success();
    }
}
