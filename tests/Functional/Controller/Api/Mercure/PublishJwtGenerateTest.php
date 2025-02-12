<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional\Controller\Api\Mercure;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Tocda\Controller\Api\Mercure\PublishJwtGenerate;
use Tocda\Infrastructure\ApiResponse\ApiResponse;
use Tocda\Infrastructure\ApiResponse\ApiResponseFactory;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseData;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Tocda\Infrastructure\ApiResponse\Exception\Error\ListError;
use Tocda\Infrastructure\Mercure\MercureJwtGenerator;
use Tocda\Infrastructure\Serializer\TocdaSerializer;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;

#[
    CoversClass(PublishJwtGenerate::class),
    CoversClass(MercureJwtGenerator::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ListError::class),
    CoversClass(TocdaSerializer::class)
]
class PublishJwtGenerateTest extends TocdaFunctionalTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
    }

    public function testSendToken(): void
    {
        $this->client->request('GET', '/api/mercure/jwt/pub');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertJson($content);

        $response = json_decode($content, true);

        self::assertArrayHasKey('tokenMercurePublish', $response['data']);
        self::assertIsString($response['data']['tokenMercurePublish']);
    }
}
