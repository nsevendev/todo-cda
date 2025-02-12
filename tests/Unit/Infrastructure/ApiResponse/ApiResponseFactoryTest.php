<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Infrastructure\ApiResponse;

use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Infrastructure\ApiResponse\ApiResponse;
use Tocda\Infrastructure\ApiResponse\ApiResponseFactory;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseData;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\ApiResponse\Exception\Error\ListError;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ListError::class),
    CoversClass(Error::class),
]
class ApiResponseFactoryTest extends TocdaUnitTestCase
{
    /** @var array<string, mixed> */
    private array $data;

    /** @var array<string, mixed> */
    private array $meta;

    /** @var array<string, mixed> */
    private array $links;

    protected function setUp(): void
    {
        parent::setUp();

        $this->data = ['key' => 'value'];
        $this->meta = ['page' => 1];
        $this->links = ['self' => '/api/resource'];
    }

    public function testSuccessApiResponse(): void
    {
        $response = ApiResponseFactory::success(
            data: $this->data,
            meta: $this->meta,
            links: $this->links,
            message: 'Test success'
        );

        $content = $response->getContent();
        self::assertIsString($content);
        $responseData = json_decode($content, true);

        self::assertIsArray($responseData);
        self::assertSame(200, $response->getStatusCode());
        self::assertSame('Test success', $responseData['message']);
        self::assertSame($this->data, $responseData['data']);
        self::assertSame($this->meta, $responseData['meta']);
        self::assertSame(null, $responseData['errors']);
        self::assertSame($this->links, $responseData['links']);
    }

    public function testCreatedApiResponse(): void
    {
        $response = ApiResponseFactory::created(
            data: $this->data,
        );

        $content = $response->getContent();
        self::assertIsString($content);
        $responseData = json_decode($content, true);

        self::assertIsArray($responseData);
        self::assertSame(201, $response->getStatusCode());
        self::assertSame('Resource created', $responseData['message']);
        self::assertSame(null, $responseData['errors']);
        self::assertSame($this->data, $responseData['data']);
    }

    public function testAcceptedApiResponse(): void
    {
        $response = ApiResponseFactory::accepted(
            data: $this->data,
        );

        $content = $response->getContent();
        self::assertIsString($content);
        $responseData = json_decode($content, true);

        self::assertIsArray($responseData);
        self::assertSame(202, $response->getStatusCode());
        self::assertSame('Request accepted', $responseData['message']);
        self::assertSame(null, $responseData['errors']);
        self::assertSame($this->data, $responseData['data']);
    }

    public function testNoContentApiResponse(): void
    {
        $response = ApiResponseFactory::noContent();

        $content = $response->getContent();
        self::assertIsString($content);
        $responseData = json_decode($content, true);

        self::assertIsArray($responseData);
        self::assertSame(204, $response->getStatusCode());
        self::assertSame('No Content', $responseData['message']);
        self::assertSame(null, $responseData['errors']);
    }

    public function testToExceptionApiResponse(): void
    {
        $response = ApiResponseFactory::toException(
            statusCode: 500,
            message: 'Test exception',
            errors: [Error::create('Test error', 'test_error')],
        );

        $content = $response->getContent();
        self::assertIsString($content);

        $responseData = json_decode($content, true);
        self::assertIsArray($responseData);

        self::assertSame(500, $response->getStatusCode());
        self::assertSame('Test exception', $responseData['message']);
        self::assertSame(null, $responseData['data']);
        self::assertSame(null, $responseData['meta']);
        self::assertSame(null, $responseData['links']);

        self::assertArrayHasKey('errors', $responseData);
        self::assertIsArray($responseData['errors']);

        $expectedError = ['key' => 'Test error', 'message' => 'test_error'];
        self::assertContains($expectedError, $responseData['errors']);
    }
}
