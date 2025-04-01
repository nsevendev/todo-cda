<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional\Controller\Api\Mallo;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Tocda\Controller\Api\Mallo\ListMallo;
use Tocda\Entity\Mallo\Dto\MalloDto;
use Tocda\Entity\Mallo\Mallo;
use Tocda\Infrastructure\ApiResponse\ApiResponse;
use Tocda\Infrastructure\ApiResponse\ApiResponseFactory;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseData;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Tocda\Infrastructure\ApiResponse\Exception\Error\ListError;
use Tocda\Infrastructure\Serializer\TocdaSerializer;
use Tocda\Message\Query\Mallo\GetListMalloHandler;
use Tocda\Repository\Mallo\MalloRepository;
use Tocda\Tests\Faker\Entity\Mallo\MalloFaker;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;

#[
    CoversClass(ListMallo::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(MalloDto::class),
    CoversClass(TocdaSerializer::class),
    CoversClass(GetListMalloHandler::class),
    CoversClass(MalloRepository::class),
    CoversClass(Mallo::class)
]
class ListMalloTest extends TocdaFunctionalTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testInvokeReturnsExpectedResponse(): void
    {
        $this->client->request('GET', '/api/list-mallo');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($content);

        $response = json_decode($content, true);

        self::assertArrayHasKey('data', $response);
    }

    public function testCreateAndRetrieveMallo(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        $mallo = MalloFaker::new();

        $entityManager->persist($mallo);
        $entityManager->flush();

        $this->client->request('GET', '/api/list-mallo');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($content);

        $response = json_decode($content, true);
        self::assertArrayHasKey('data', $response);
        self::assertNotEmpty($response['data']);

        $retrievedMallo = $response['data'][0];
        self::assertSame('Harry', $retrievedMallo['firstname']);
        self::assertSame('Potter', $retrievedMallo['lastname']);
        self::assertSame(13, $retrievedMallo['number']);

        $entityManager->rollback();
    }
}
