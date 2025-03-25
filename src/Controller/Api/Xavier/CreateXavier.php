<?php

declare(strict_types=1);

namespace Tocda\Controller\Api\Xavier;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;
use Tocda\Entity\Xavier\Dto\XavierCreateDto;
use Tocda\Infrastructure\ApiResponse\ApiResponseFactory;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Xavier\XavierInvalidArgumentException;
use Tocda\Infrastructure\Controller\AbstractTocdaController;
use Tocda\Message\Command\Xavier\CreateXavierCommand;

#[AsController]
class CreateXavier extends AbstractTocdaController
{
    /**
     * @throws ExceptionInterface
     * @throws XavierInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/xavier', name: 'tocda_api_create_xavier', methods: ['POST'])]
    public function __invoke(
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var XavierCreateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: XavierCreateDto::class,
            fnException: fn (array $errors) => new XavierInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch(
            new CreateXavierCommand(
                xavierEntityCreateDto: $dto
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La demande a été prise en compte.']);
    }
}
