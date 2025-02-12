<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Infrastructure\Mercure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Mercure\HubInterface;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\Mercure\MercurePublish;
use Tocda\Tests\Unit\TocdaUnitTestCase;

/**
 * il y a que le test pour le throw, car le reste de la class est tester dans le controller, handler etc ...
 */
#[
    CoversClass(MercurePublish::class),
    CoversClass(MercureInvalidArgumentException::class),
    CoversClass(Error::class),
]
class MercurePublishTest extends TocdaUnitTestCase
{
    /**
     * @throws Exception
     */
    public function testPublishThrowsExceptionOnInvalidJson(): void
    {
        // Création d'un mock pour HubInterface, car nous ne testons pas sa fonctionnalité ici
        $hubMock = $this->createMock(HubInterface::class);

        // Instanciation de la classe à tester
        $mercurePublish = new MercurePublish($hubMock);

        // Données invalides (les objets PHP circulaires ne peuvent pas être encodés en JSON)
        $invalidData = ['key' => tmpfile()]; // tmpfile() est un exemple de ressource non encodable

        $this->expectException(MercureInvalidArgumentException::class);
        $this->expectExceptionMessage('Données invalides à publier');

        $mercurePublish->publish('/test', $invalidData);
    }
}
