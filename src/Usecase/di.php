<?php

declare(strict_types=1);

namespace App\Usecase;

use App\Usecase\Services\AddUsersService;
use App\Usecase\Services\ClearUsersService;
use App\Usecase\Services\ListUsersService;
use App\Usecase\Services\SendEmailsService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()
        ->set(AddUsersService::class)
        ->set(ClearUsersService::class)
        ->set(ListUsersService::class)
        ->set(SendEmailsService::class);
};
