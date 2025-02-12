<?php

declare(strict_types=1);

namespace Tocda\Controller\Api\Ping;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;
use Tocda\Entity\Ping\Dto\PingCreateDto;
use Tocda\Infrastructure\ApiResponse\ApiResponseFactory;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Ping\PingInvalidArgumentException;
use Tocda\Infrastructure\Controller\AbstractTocdaController;
use Tocda\Message\Command\Ping\CreatePingCommand;

#[AsController]
class CreatePing extends AbstractTocdaController
{
    /**
     * @throws ExceptionInterface
     * @throws PingInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/ping', name: 'tocda_api_create_ping', methods: ['POST'])]
    public function __invoke(
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var PingCreateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: PingCreateDto::class,
            fnException: fn (array $errors) => new PingInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch(
            new CreatePingCommand(
                pingEntityCreateDto: $dto
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La demande a été prise en compte.']);
    }
}
