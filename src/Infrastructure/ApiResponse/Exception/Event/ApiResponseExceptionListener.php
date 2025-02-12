<?php

declare(strict_types=1);

namespace Tocda\Infrastructure\ApiResponse\Exception\Event;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Tocda\Infrastructure\ApiResponse\Exception\Type\ApiResponseExceptionInterface;

class ApiResponseExceptionListener
{
    #[AsEventListener(event: ExceptionEvent::class, priority: 0)]
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApiResponseExceptionInterface) {
            $event->setResponse($exception->toApiResponse());
        }

    }
}
