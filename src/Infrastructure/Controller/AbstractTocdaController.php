<?php

declare(strict_types=1);

namespace Tocda\Infrastructure\Controller;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\Serializer\TocdaSerializer;

abstract class AbstractTocdaController
{
    public function __construct(protected TocdaSerializer $serializer, protected ValidatorInterface $validator) {}

    /**
     * Valide une liste de contraintes et lève une exception personnalisée en cas d'erreurs.
     */
    private function validateArgumentList(ConstraintViolationListInterface $errors, callable $fnException): void
    {
        $errorList = [];

        if ($errors->count() > 0) {
            foreach ($errors as $error) {
                $errorList[] = Error::create(
                    key: $error->getPropertyPath(),
                    message: (string) $error->getMessage()
                );
            }

            throw $fnException($errorList);
        }
    }

    /**
     * Désérialise et valide un DTO.
     *
     * @throws Throwable
     */
    protected function deserializeAndValidate(
        string $data,
        string $dtoClass,
        callable $fnException,
    ): object {
        // Désérialisation
        $dto = $this->serializer->deserialize($data, $dtoClass, 'json');

        // Validation
        $this->validate(
            errors: $this->validator->validate($dto),
            fnException: $fnException
        );

        return $dto;
    }

    /**
     * @throws Throwable
     */
    protected function validate(ConstraintViolationListInterface $errors, callable $fnException): void
    {
        $this->validateArgumentList($errors, $fnException);
    }
}
