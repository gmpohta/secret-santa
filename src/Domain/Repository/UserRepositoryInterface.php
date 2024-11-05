<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Dto\RequestListDto;
use App\Domain\Dto\ResponseListDto;
use App\Domain\Models\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function list(?RequestListDto $dto = null): ResponseListDto;

    public function clear(): void;

    public function save(User $user): void;

    public function flush(): void;
}
