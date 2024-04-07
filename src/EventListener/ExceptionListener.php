<?php

namespace App\EventListener;

use App\Util\HttpResponseUtil;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

final class ExceptionListener
{
    public function __construct(
        private KernelInterface $kernel,
        private HttpResponseUtil $httpResponseUtil
    ) {
    }

    #[AsEventListener(event: KernelEvents::EXCEPTION)]
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($this->kernel->getEnvironment() === 'dev') {
            dump($exception);
        }

        $content = $this->prepareResponse($exception);
        $event->setResponse($content);
    }

    private function prepareResponse(\Throwable $exception): JsonResponse
    {
        $exceptionMessage = $exception->getMessage();
        $exceptionCode = $exception->getCode();
        $exceptionResponseCode = $this->httpResponseUtil->isValidHttpCode($exceptionCode)
            ? $exceptionCode
            : JsonResponse::HTTP_INTERNAL_SERVER_ERROR;

        return new JsonResponse($exceptionMessage, $exceptionResponseCode);
    }
}
