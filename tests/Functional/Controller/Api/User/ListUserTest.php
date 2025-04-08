<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional\Controller\Api\Ping;

use Doctrine\DBAL\Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Tocda\Controller\Api\User\ListUser;
use Tocda\Entity\User\Dto\UserDto;
use Tocda\Entity\User\User;
use Tocda\Entity\User\ValueObject\UserEmail;
use Tocda\Entity\User\ValueObject\UserUsername;
use Tocda\Entity\User\ValueObject\UserPassword;
use Tocda\Infrastructure\ApiResponse\ApiResponse;
use Tocda\Infrastructure\ApiResponse\ApiResponseFactory;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseData;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\User\UserInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\ListError;
use Tocda\Infrastructure\Doctrine\Types\User\UserEmailType;
use Tocda\Infrastructure\Doctrine\Types\User\UserUsernameType;
use Tocda\Infrastructure\Doctrine\Types\User\UserPasswordType;
use Tocda\Infrastructure\Serializer\TocdaSerializer;
use Tocda\Message\Query\User\GetListUserHandler;
use Tocda\Repository\User\UserRepository;
use Tocda\Tests\Faker\Entity\User\UserFaker;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;

#[
    CoversClass(ListUser::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(UserDto::class),
    CoversClass(TocdaSerializer::class),
    CoversClass(GetListUserHandler::class),
    CoversClass(UserRepository::class),
    CoversClass(User::class),
    CoversClass(UserEmail::class),
    CoversClass(UserEmailType::class),
    CoversClass(UserUsername::class),
    CoversClass(UserUsernameType::class),
    CoversClass(UserPassword::class),
    CoversClass(UserPasswordType::class),
]
class ListUserTest extends TocdaFunctionalTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testInvokeReturnsExpectedResponse(): void
    {
        $this->client->request('GET', '/api/v1/users');
        
        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson((string) $content);

        $response = json_decode((string) $content, true);

        self::assertArrayHasKey('data', $response);
    }

    /**
     * @throws Exception
     * @throws UserInvalidArgumentException
     */
    public function testCreateAndRetrieveUser(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        $user = UserFaker::new();

        $entityManager->persist($user);
        $entityManager->flush();

        $this->client->request('GET', '/api/list-user');

        $content = $this->client->getResponse()->getContent();

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson((string) $content);


        $response = json_decode((string) $content, true);
        self::assertArrayHasKey('data', $response);
        self::assertArrayHasKey('data', $response);
        self::assertNotEmpty($response['data']);

        $retrievedUser = $response['data'][0];
        self::assertSame('paquito', $retrievedUser['username']);
        self::assertSame('paquito@gmail.com', $retrievedUser['email']);
        self::assertSame('Paquito123?', $retrievedUser['password']);

        $entityManager->remove($user);
    }
}