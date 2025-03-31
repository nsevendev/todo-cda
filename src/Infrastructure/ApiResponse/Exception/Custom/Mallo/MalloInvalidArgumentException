<?php

declare(strict_types=1);

namespace Tocda\Infrastructure\ApiResponse\Exception\Custom\Mallo;

use Symfony\Component\HttpFoundation\Response;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;

class MalloInvalidArgumentException extends AbstractApiResponseException
{
    private string $firstname;
    private string $lastname;
    private string|int $number;

    /**
     * @param array<Error>|null $errors
     */
    public function __construct(
        string $firstname,
        string $lastname,
        string|int $number,
        string $getMessage = '',
        int $statusCode = 422,
        ?array $errors = null,
    ) {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->number = $number;

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

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getNumber(): string|int
    {
        return $this->number;
    }
}
