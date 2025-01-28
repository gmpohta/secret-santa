<?php

declare(strict_types=1);

namespace App\Usecase\MessageHandlers;

use App\Domain\Messages\Message;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class MessageHandler
{
    public function __construct(
        private LoggerInterface $syslogLogger,
    ) {}

    /** @throws \Exception */
    public function __invoke(Message $message): void
    {
        file_put_contents(
            'success',
            'try ' . $message->id . '  ' . $message->isSuccess . '; ',
            FILE_APPEND,
        );

        $this->syslogLogger->error(message: 'try ' . $message->id . '  ' . $message->isSuccess . '; ');

        if (!$message->isSuccess) {
            throw new \Exception();
        }
    }
}
