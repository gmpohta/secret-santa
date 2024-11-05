<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\Mailer\MailerInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\Mailer\Mailer;
use App\Infrastructure\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()
        ->set(UserRepository::class)
            ->args([
                '$redisHost' => '%env(REDIS_HOST)%',
                '$redisPort' => '%env(int:REDIS_PORT)%',
            ])
            ->alias(UserRepositoryInterface::class, UserRepository::class)
        ->set(Mailer::class)
            ->args([
                '$emailAddress' => '%env(MAILER_EMAIL_ADDRESS)%',
            ])
            ->alias(MailerInterface::class, Mailer::class);
};
