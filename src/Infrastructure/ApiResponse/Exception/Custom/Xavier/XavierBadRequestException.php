<?php

declare(strict_types=1);

namespace Tocda\Infrastructure\ApiResponse\Exception\Custom\Xavier;

use Symfony\Component\HttpFoundation\Response;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;

class XavierBadRequestException extends AbstractApiResponseException
{
    /**
     * @param array<Error>|null $errors
     */
    public function __construct(
        string $getMessage = '',
        int $statusCode = Response::HTTP_BAD_REQUEST,
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
