<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Dto\RequestListDto;
use App\Domain\Dto\ResponseListDto;
use App\Domain\Models\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Serializer\SerializerEnum;
use App\Domain\Serializer\SerializerFactory;
use App\Domain\Serializer\SerializerInterface;

final readonly class UserRepository implements UserRepositoryInterface
{
    private \Redis $redisConnection;

    private SerializerInterface $serializer;

    public function __construct(
        private string $redisHost,
        private int $redisPort,
    ) {
        $this->redisConnection = new \Redis();
        $this->redisConnection->connect($this->redisHost, $this->redisPort);
        $this->serializer = SerializerFactory::create(SerializerEnum::JSON_SERIALIZER);
    }

    public function findByEmail(string $email): ?User
    {
        $userId = $this->redisConnection->get('user:email:' . $email);

        if (false === $userId || ! \is_string($userId)) {
            return null;
        }

        $userData = $this->redisConnection->get('user:' . $userId);

        if (\is_string($userData)) {
            return $this->serializer->deserialize($userData, User::class);
        }

        return null;
    }

    public function list(?RequestListDto $dto = null): ResponseListDto
    {
        $users = [];
        $keys = (array) $this->redisConnection->keys('user:*');

        if (0 === \count($keys)) {
            new ResponseListDto(
                items: $users,
                pagination: [
                    'totalItems' => 0,
                    'itemsPerPage' => $dto?->limit ?? 0,
                    'totalPages' => 0,
                    'currentPage' => $dto?->page ?? 0,
                ],
            );
        }

        foreach ($keys as $key) {
            if (str_starts_with((string) $key, 'user:email:')) {
                continue;
            }

            $userData = $this->redisConnection->get((string) $key);
            if (\is_string($userData)) {
                $users[] = $this->serializer->deserialize($userData, User::class);
            }
        }

        $count = \count($users);

        if (null !== $dto) {
            $offset = ($dto->page - 1) * $dto->limit;
            $users = \array_slice($users, $offset, $dto->limit);
        }

        return new ResponseListDto(
            items: $users,
            pagination: [
                'totalItems' => $count,
                'itemsPerPage' => $dto?->limit,
                'totalPages' => null !== $dto ? (int) ceil($count / $dto->limit) : 0,
                'currentPage' => $dto?->page,
            ],
        );
    }

    public function clear(): void
    {
        $keys = (array) $this->redisConnection->keys('user:*');
        foreach ($keys as $key) {
            $this->redisConnection->del((string) $key);
        }
    }

    public function save(User $user): void
    {
        $userData = $this->serializer->serialize($user);
        $this->redisConnection->set('user:' . $user->getId(), $userData);

        $this->redisConnection->set('user:email:' . $user->getEmail(), $user->getId());
    }

    public function flush(): void {}
}
