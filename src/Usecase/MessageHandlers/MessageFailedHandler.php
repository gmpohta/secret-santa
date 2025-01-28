<?php

declare(strict_types=1);

namespace App\Usecase\MessageHandlers;

use App\Domain\Messages\FailedMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class MessageFailedHandler
{
    public function __construct(
        private LoggerInterface $syslogLogger,
    ) {}

    public function __invoke(FailedMessage $message): void
    {
        file_put_contents(
            'failed',
            'failed ' . $message->originalMessageId . '  ' . $message->reason . '; ',
            FILE_APPEND,
        );

        $this->syslogLogger->error(message: 'failed ' . $message->originalMessageId . '  ' . $message->reason . '; ');
    }
}
