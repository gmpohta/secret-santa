<?php

declare(strict_types=1);

namespace App\Usecase\Services;

use App\Domain\Dto\RequestListDto;
use App\Domain\Dto\ResponseListDto;
use App\Domain\Repository\UserRepositoryInterface;

final readonly class ListUsersService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(RequestListDto $dto): ResponseListDto
    {
        if (2000 < $dto->limit) {
            $dto->limit = 2000;
        }

        return $this->userRepository->list($dto);
    }
}
