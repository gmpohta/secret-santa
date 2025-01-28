<?php

declare(strict_types=1);

namespace App\Usecase\MessageHandlers;

use App\Domain\Messages\FailedMessage;
use App\Domain\Messages\Message;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class FailedMessageSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            WorkerMessageFailedEvent::class => 'onMessageFailed',
        ];
    }

    public function onMessageFailed(WorkerMessageFailedEvent $event): void
    {
        $envelope = $event->getEnvelope();
        $message = $envelope->getMessage();

        if ($event->willRetry()) {
            return;
        }

        if (!$message instanceof Message) {
            $failedMessage = new FailedMessage(
                originalMessageId: (string) ($message->id ?? 'unknown'),
                reason: $event->getThrowable()->getMessage(),
            );

            $this->messageBus->dispatch($failedMessage);
        }
    }
}
