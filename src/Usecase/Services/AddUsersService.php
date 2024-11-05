<?php

declare(strict_types=1);

namespace App\Usecase\Services;

use App\Domain\Models\User;
use App\Domain\Repository\UserRepositoryInterface;

final readonly class AddUsersService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    /**
     * @param array<int, array<string,mixed>> $userArrays
     * @return string[]
     */
    public function __invoke(array $userArrays): array
    {
        $users = [];
        foreach ($userArrays as $userArray) {
            $users[] = (new User())
                ->setEmail((string) ($userArray['email'] ?? ''))
                ->setFullName((string) ($userArray['fullName'] ?? ''));
        }

        $errors = $this->validate($users);
        if (0 < \count($errors)) {
            return $errors;
        }

        foreach ($users as $user) {
            $this->userRepository->save($user);
        }

        $this->userRepository->flush();

        return [];
    }

    /**
     * @param User[] $users
     * @return string[]
     */
    private function validate(array $users): array
    {
        $errors = [];

        foreach ($users as $user) {
            if (3 !== \count(explode(' ', trim($user->getFullName())))) {
                $errors[] = "Полное имя: {$user->getFullName()} должно состоять из трех слов.";
            }

            if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Некорректный email: ' . $user->getEmail();
            }

            if (null !== $this->userRepository->findByEmail($user->getEmail())) {
                $errors[] = 'Пользователь c email: ' . $user->getEmail() . ' уже существует.';
            }
        }

        return $errors;
    }
}
