<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\Infrastructure\ApiResponse\Exception\Custom;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Tocda\Infrastructure\ApiResponse\ApiResponse;
use Tocda\Infrastructure\ApiResponse\ApiResponseFactory;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseData;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Ping\PingInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\ApiResponse\Exception\Error\ListError;
use Tocda\Infrastructure\ApiResponse\Exception\Event\ApiResponseExceptionListener;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[
    CoversClass(ApiResponseExceptionListener::class),
    CoversClass(PingInvalidArgumentException::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ListError::class),
    CoversClass(Error::class),
]
class PingInvalidArgumentExceptionTest extends TocdaUnitTestCase
{
    private ApiResponseExceptionListener $listener;

    private HttpKernelInterface $kernel;

    private Request $request;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->listener = new ApiResponseExceptionListener();
        $this->kernel = $this->createMock(HttpKernelInterface::class);
        $this->request = Request::create('/api/ping');
    }

    public function testOnKernelExceptionPingInvalidArgumentException(): void
    {
        $exceptionCustom = new PingInvalidArgumentException();
        $event = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $exceptionCustom
        );

        $this->listener->onKernelException($event);

        self::assertSame(422, $exceptionCustom->getStatusCode());

        // Vérifie que le listener a défini une réponse
        $response = $event->getResponse();
        self::assertNotNull($response, 'Response should not be null');
        self::assertInstanceOf(JsonResponse::class, $response);

        $content = $response->getContent();
        self::assertIsString($content);

        // Vérifie le contenu de la réponse JSON
        $responseData = json_decode($content, true);
        self::assertIsArray($responseData);
        self::assertSame(422, $responseData['statusCode']);
        self::assertSame('Unprocessable Content', $responseData['message']);
        self::assertSame(null, $responseData['data']);
        self::assertSame(null, $responseData['meta']);
        self::assertSame(null, $responseData['links']);

        $expectedError = ['key' => 'error', 'message' => 'Unprocessable Content'];
        self::assertContains($expectedError, $responseData['errors']);
    }
}
