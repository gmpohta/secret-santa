<?php

declare(strict_types=1);

namespace App\Api\SendEmails;

use App\Infrastructure\Framework\AbstractController;
use App\Usecase\Services\SendEmailsService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class Action extends AbstractController
{
    public function __construct(
        private readonly SendEmailsService $sendEmailsService,
    ) {}

    #[Route(path: '/api/v1/send-emails', name: 'api_v1_send_emails', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $errors = ($this->sendEmailsService)();

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
