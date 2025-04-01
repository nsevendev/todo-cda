<?php

declare(strict_types=1);

namespace Functional\Controller\Api\Ping;

use Doctrine\DBAL\Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Tocda\Controller\Api\Ping\DeletePing;
use Tocda\Controller\Api\Ping\ListPing;
use Tocda\Entity\Ping\Dto\PingDto;
use Tocda\Entity\Ping\Ping;
use Tocda\Entity\Ping\ValueObject\PingMessage;
use Tocda\Entity\Ping\ValueObject\PingStatus;
use Tocda\Infrastructure\ApiResponse\ApiResponse;
use Tocda\Infrastructure\ApiResponse\ApiResponseFactory;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseData;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Tocda\Infrastructure\ApiResponse\Exception\Error\ListError;
use Tocda\Infrastructure\Doctrine\Types\Ping\PingMessageType;
use Tocda\Infrastructure\Doctrine\Types\Ping\PingStatusType;
use Tocda\Infrastructure\Serializer\TocdaSerializer;
use Tocda\Message\Command\Ping\DeletePingCommand;
use Tocda\Message\Query\Ping\GetListPingHandler;
use Tocda\Repository\Ping\PingRepository;
use Tocda\Tests\Faker\Entity\Ping\PingFaker;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;

#[
    CoversClass(ListPing::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(PingDto::class),
    CoversClass(TocdaSerializer::class),
    CoversClass(GetListPingHandler::class),
    CoversClass(PingRepository::class),
    CoversClass(Ping::class),
    CoversClass(PingMessage::class),
    CoversClass(PingMessageType::class),
    CoversClass(PingStatus::class),
    CoversClass(PingStatusType::class),
    CoversClass(DeletePing::class),
    CoversClass(DeletePingCommand::class)
]
class DeletePingTest extends TocdaFunctionalTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @throws Exception
     */
    public function testCreateAndDeletePing(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        $ping = PingFaker::new();

        $entityManager->persist($ping);
        $entityManager->flush();

        $this->client->request('DELETE', '/api/ping/'.$ping->id());

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $entityManager->rollBack();
    }
}
