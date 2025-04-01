<?php

declare(strict_types=1);

namespace Tocda\Tests\Functional\Controller\Api\Mallo;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Tocda\Controller\Api\Mallo\CreateMallo;
use Tocda\Entity\Mallo\Dto\MalloCreateDto;
use Tocda\Entity\Mallo\ValueObject\MalloFirstname;
use Tocda\Entity\Mallo\ValueObject\MalloLastname;
use Tocda\Entity\Mallo\ValueObject\MalloNumber;
use Tocda\Infrastructure\ApiResponse\ApiResponse;
use Tocda\Infrastructure\ApiResponse\ApiResponseFactory;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseData;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mallo\MalloInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\ApiResponse\Exception\Error\ListError;
use Tocda\Infrastructure\ApiResponse\Exception\Event\ApiResponseExceptionListener;
use Tocda\Infrastructure\Serializer\Normalizer\ValueObjectNormalizer;
use Tocda\Infrastructure\Serializer\TocdaSerializer;
use Tocda\Message\Command\Mallo\CreateMalloCommand;
use Tocda\Tests\Functional\TocdaFunctionalTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(CreateMalloCommand::class),
    CoversClass(TocdaSerializer::class),
    CoversClass(CreateMallo::class),
    CoversClass(MalloFirstname::class),
    CoversClass(MalloLastname::class),
    CoversClass(MalloNumber::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(ValueObjectNormalizer::class),
    CoversClass(MalloCreateDto::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(MalloInvalidArgumentException::class),
    CoversClass(Error::class),
    CoversClass(ApiResponseExceptionListener::class)
]
class CreateMalloTest extends TocdaFunctionalTestCase
{
    use InteractsWithMessenger;

    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testInvokeReturnResponseSucces(): void
    {
        $payload = json_encode([ // payload est la donnée transmise dans une requête ou une réponse HTTP
            'firstname' => 'Mallo',
            'lastname' => 'Zimmermann',
            'number' => 67,
        ]);

        $this->client->request('POST', '/api/mallo', [], [], [], $payload);

        // Vérifie que la réponse est correcte
        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseContent = $this->client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertArrayHasKey('message', $responseData['data']);
        $this->assertSame('La demande a été prise en compte.', $responseData['data']['message']);

        // Vérifie que le message a bien été envoyé dans le bus
        /* @var InMemoryTransport $transport */
        $this->transport('async')->queue()->assertNotEmpty();
    }

    public function testInvokeInvalidateArgument(): void
    {
        $payload = json_encode([
            'firstname' => 'Harry',
            'lastname' => '',
            'number' => 12,
        ]);

        $this->client->request('POST', '/api/mallo', [], [], [], $payload);

        // Vérifie que la réponse retourne une erreur 422 (Unprocessable Entity)
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $responseContent = $this->client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertArrayHasKey('errors', $responseData);
        $this->assertNotEmpty($responseData['errors']);
    }
}
