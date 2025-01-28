<?php

declare(strict_types=1);

namespace App\Usecase\MessageHandlers;

use App\Domain\Messages\Message2;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class MessageHandler2
{
    public function __construct(
        private LoggerInterface $syslogLogger,
    ) {}

    /** @throws \Exception */
    public function __invoke(Message2 $message): never
    {
        file_put_contents(
            'success',
            'try2 ' . $message->id . '; ',
            FILE_APPEND,
        );

        $this->syslogLogger->error(message: 'try ' . $message->id . '; ');

        throw new \Exception();
    }
}
