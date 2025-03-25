<?php

declare(strict_types=1);

namespace Tocda\Infrastructure\ApiResponse\Exception\Custom\Xavier;

use Symfony\Component\HttpFoundation\Response;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;

class XavierInvalidArgumentException extends AbstractApiResponseException
{
    /**
     * @param array<Error>|null $errors
     */
    public function __construct(
        string $getMessage = '',
        int $statusCode = 422,
        ?array $errors = null,
    ) {
        $statusTexts = Response::$statusTexts;

        if ('' === $getMessage && true === array_key_exists($statusCode, $statusTexts)) {
            $getMessage = $statusTexts[$statusCode];
        }

        parent::__construct(
            getMessage: $getMessage,
            statusCode: $statusCode,
            errors: $errors
        );
    }
}
