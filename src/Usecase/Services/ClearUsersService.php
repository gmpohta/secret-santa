<?php

declare(strict_types=1);

namespace App\Usecase\Services;

use App\Domain\Repository\UserRepositoryInterface;

final readonly class ClearUsersService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(): void
    {
        $this->userRepository->clear();
        $this->userRepository->flush();
    }
}
