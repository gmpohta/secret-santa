<?php

declare(strict_types=1);

namespace App\Usecase\Services;

use App\Domain\Mailer\MailerInterface;
use App\Domain\Models\User;
use App\Domain\Repository\UserRepositoryInterface;

final readonly class SendEmailsService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private MailerInterface $mailer,
    ) {}

    /** @return string[] */
    public function __invoke(): array
    {
        /** @var User[] */
        $users = $this->userRepository->list()->items;

        $errors = [];
        if (4 > \count($users)) {
            $errors[] = 'Для игры необходимо минимум 4 участника.';

            return $errors;
        }

        if (0 !== \count($users) % 2) {
            $errors[] = 'Для игры необходимо четное количество участников.';

            return $errors;
        }

        $pairs = $this->generateRandomPairs($users);

        foreach ($pairs as $pair) {
            $santa = $pair[0];
            $recipient = $pair[1];

            try {
                $this->mailer->sendEmail(
                    to: $santa->getEmail(),
                    subject: 'Тайный Санта - Известен получатель подарка',
                    content: "Привет, {$santa->getFullName()}. Вы Тайный Санта! Нужно поздравить {$recipient->getFullName()} и подарить ему подарок.",
                );
            } catch (\Throwable $e) {
                $errors[] = $e->getMessage();
            }
        }

        return $errors;
    }

    /**
     * @param User[] $users
     * @return array<array-key,array<User>>
     */
    private function generateRandomPairs(array $users): array
    {
        shuffle($users);

        $pairs = [];
        for ($i = 0; $i < \count($users); $i += 2) {
            $pairs[] = [$users[$i], $users[$i + 1]];
        }

        return $pairs;
    }
}
