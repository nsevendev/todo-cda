<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional\Controller\Api\Ping;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Tocda\Controller\Api\Ping\ListPing;
use Tocda\Entity\Ping\Dto\PingDto;
use Tocda\Entity\Ping\Ping;
use Tocda\Infrastructure\ApiResponse\ApiResponse;
use Tocda\Infrastructure\ApiResponse\ApiResponseFactory;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseData;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Tocda\Infrastructure\ApiResponse\Exception\Error\ListError;
use Tocda\Infrastructure\Serializer\TocdaSerializer;
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
    CoversClass(Ping::class)
]
class ListPingTest extends TocdaFunctionalTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testInvokeReturnsExpectedResponse(): void
    {
        $this->client->request('GET', '/api/list-ping');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($content);

        $response = json_decode($content, true);

        self::assertArrayHasKey('data', $response);
    }

    public function testCreateAndRetrievePing(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        $ping = PingFaker::new();

        $entityManager->persist($ping);
        $entityManager->flush();

        $this->client->request('GET', '/api/list-ping');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($content);

        $response = json_decode($content, true);
        self::assertArrayHasKey('data', $response);
        self::assertNotEmpty($response['data']);

        $retrievedPing = $response['data'][0];
        self::assertSame(200, $retrievedPing['status']);
        self::assertSame("c'est bon", $retrievedPing['message']);

        $entityManager->rollback();
    }
}
