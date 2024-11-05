<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Framework;

use App\Infrastructure\Framework\ApiExceptionSubscriber;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class ApiExceptionSubscriberTest extends TestCase
{
    private ApiExceptionSubscriber $subscriber;

    protected function setUp(): void
    {
        $loggerMock = $this->createMock(LoggerInterface::class);
        $this->subscriber = new ApiExceptionSubscriber($loggerMock);
    }

    public function testHttpExceptionHandling(): void
    {
        $exception = new HttpException(Response::HTTP_NOT_FOUND, 'Not Found');
        $event = new ExceptionEvent($this->createMock(HttpKernelInterface::class), $this->createMock(Request::class), 0, $exception);

        $this->subscriber->onKernelException($event);

        $response = $event->getResponse();

        self::assertEquals([KernelEvents::EXCEPTION => 'onKernelException'], $this->subscriber::getSubscribedEvents());
        self::assertInstanceOf(Response::class, $response);
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testOtherException(): void
    {
        $exception = new \Exception('error');
        $event = new ExceptionEvent($this->createMock(HttpKernelInterface::class), $this->createMock(Request::class), 0, $exception);

        $this->subscriber->onKernelException($event);

        $response = $event->getResponse();

        self::assertInstanceOf(Response::class, $response);
        self::assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }
}
