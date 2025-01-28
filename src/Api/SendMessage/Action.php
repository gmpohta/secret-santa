<?php

declare(strict_types=1);

namespace App\Api\SendMessage;

use App\Domain\Messages\Message;
use App\Domain\Messages\Message2;
use App\Infrastructure\Framework\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

final class Action extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {}

    #[Route(path: '/api/v1/send-message/{id}/{success}', name: 'api_v1_send_message', methods: ['GET'])]
    public function __invoke(string $id, int $success): JsonResponse
    {
        $this->messageBus->dispatch(new Message($id, 1 === $success));

        $this->messageBus->dispatch(new Message2($id));

        return $this->success();
    }
}
