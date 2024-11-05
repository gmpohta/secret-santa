<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework;

use App\Domain\Exceptions\AbstractAppException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class ApiExceptionSubscriber implements EventSubscriberInterface
{
    use HasJsonResponse;

    public function __construct(
        private LoggerInterface $syslogLogger,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        if ($e instanceof HttpException) {
            $event->setResponse($this->fail(message: $e->getMessage(), code: $e->getStatusCode()));

            return;
        }

        if ($e instanceof AbstractAppException) {
            $event->setResponse($this->fail(message: $e->getMessage(), code: $e->getCode()));
            $this->syslogLogger->error(message: $e->getMessage());

            return;
        }

        $event->setResponse($this->fail(message: $e->getMessage(), code: Response::HTTP_INTERNAL_SERVER_ERROR));
    }
}
