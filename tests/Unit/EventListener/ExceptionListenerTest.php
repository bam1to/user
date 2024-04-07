<?php

namespace App\Tests\Unit\EventListener;

use App\EventListener\ExceptionListener;
use App\Util\HttpResponseUtil;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

class ExceptionListenerTest extends TestCase
{
    private EventDispatcherInterface $dispatcher;

    protected function setUp(): void
    {
        $this->dispatcher = new EventDispatcher();
    }

    public function testExceptionCorrectlyHandlesAndResponseJson()
    {
        $this->dispatcher->addListener(KernelEvents::EXCEPTION, [new ExceptionListener(
            $this->createMock(KernelInterface::class),
            $this->createMock(HttpResponseUtil::class)
        ), 'onKernelException']);

        $event = new ExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MAIN_REQUEST,
            $this->createMock(\Throwable::class)
        );

        $this->dispatcher->dispatch($event, KernelEvents::EXCEPTION);

        $this->assertInstanceOf(JsonResponse::class, $event->getResponse());
    }
}
