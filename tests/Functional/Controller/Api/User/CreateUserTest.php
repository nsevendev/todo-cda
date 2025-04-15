<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional\Controller\Api\User;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Tocda\Controller\Api\User\CreateUser;
use Tocda\Entity\User\Dto\UserCreateDto;
use Tocda\Entity\User\ValueObject\UserEmail;
use Tocda\Entity\User\ValueObject\UserPassword;
use Tocda\Entity\User\ValueObject\UserUsername;
use Tocda\Infrastructure\ApiResponse\ApiResponse;
use Tocda\Infrastructure\ApiResponse\ApiResponseFactory;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseData;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\User\UserInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\ApiResponse\Exception\Error\ListError;
use Tocda\Infrastructure\ApiResponse\Exception\Event\ApiResponseExceptionListener;
use Tocda\Infrastructure\Serializer\Normalizer\ValueObjectNormalizer;
use Tocda\Infrastructure\Serializer\TocdaSerializer;
use Tocda\Message\Command\User\CreateUserCommand;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(CreateUserCommand::class),
    CoversClass(TocdaSerializer::class),
    CoversClass(CreateUser::class),
    CoversClass(UserEmail::class),
    CoversClass(UserUsername::class),
    CoversClass(UserPassword::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(ValueObjectNormalizer::class),
    CoversClass(UserCreateDto::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(UserInvalidArgumentException::class),
    CoversClass(Error::class),
    CoversClass(ApiResponseExceptionListener::class)
]
class CreateUserTest extends TocdaFunctionalTestCase
{
    use InteractsWithMessenger;

    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testInvokeReturnResponseSucces(): void
    {
        $payload = json_encode([
            'username' => 'paquito',
            'email' => 'paquito@gmail.com',
            'password' => 'Paquito123?',
        ]);

        $this->client->request('POST', '/api/user', [], [], [], $payload);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseContent = $this->client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertArrayHasKey('message', $responseData['data']);
        $this->assertSame('La demande a été prise en compte.', $responseData['data']['message']);

        $this->transport('async')->queue()->assertNotEmpty();
    }

    public function testInvokeInvalidateArgument(): void
    {
        $payload = json_encode([
            'username' => 'paquito',
            'email' => 'paquito@gmail.com',
            'password' => 'Paquito123',
        ]);

        $this->client->request('POST', '/api/user', [], [], [], $payload);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $responseContent = $this->client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertArrayHasKey('errors', $responseData);
        $this->assertNotEmpty($responseData['errors']);
    }
}
