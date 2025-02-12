<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Infrastructure\Mercure;

use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\Mercure\MercureJwtGenerator;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[
    CoversClass(MercureJwtGenerator::class),
    CoversClass(MercureInvalidArgumentException::class),
    CoversClass(Error::class),
]
class MercureJwtGeneratorTest extends TocdaUnitTestCase
{
    public function testGeneratePublisherTokenThrowsExceptionWhenSecretIsEmpty(): void
    {
        // Instancie la classe avec un secret vide
        $mercureJwtGenerator = new MercureJwtGenerator('');

        $this->expectException(MercureInvalidArgumentException::class);
        $this->expectExceptionMessage('Mercure secret is not set');

        $mercureJwtGenerator->generatePublisherToken(['/test']);
    }

    public function testGenerateSubscriberTokenThrowsExceptionWhenSecretIsEmpty(): void
    {
        // Instancie la classe avec un secret vide
        $mercureJwtGenerator = new MercureJwtGenerator('');

        $this->expectException(MercureInvalidArgumentException::class);
        $this->expectExceptionMessage('Mercure secret is not set');

        $mercureJwtGenerator->generateSubscriberToken(['/test']);
    }
}
