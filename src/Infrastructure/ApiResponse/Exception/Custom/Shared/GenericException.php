<?php

declare(strict_types=1);

namespace Tocda\Infrastructure\ApiResponse\Exception\Custom\Shared;

use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;

class GenericException extends AbstractApiResponseException
{
    /**
     * @param array<Error>|null $errors
     */
    public function __construct(
        private Throwable $exception,
        string $getMessage = '',
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        ?array $errors = null,
    ) {
        $statusTexts = Response::$statusTexts;

        if ('' === $getMessage && true === array_key_exists($statusCode, $statusTexts)) {
            $getMessage = $statusTexts[$statusCode];
        }

        parent::__construct($getMessage, $statusCode, $errors);
    }

    protected function addErrorInfo(): void
    {
        if ('dev' === $_ENV['APP_ENV'] || 'test' === $_ENV['APP_ENV']) {
            $this->addError('file', $this->exception->getFile());
            $this->addError('line', (string) $this->exception->getLine());
            $this->addError('stack', $this->exception->getTraceAsString());
        }
    }
}
