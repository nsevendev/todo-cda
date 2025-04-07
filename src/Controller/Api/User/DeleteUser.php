<?php

declare(strict_types=1);

namespace Tocda\Controller\Api\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Tocda\Infrastructure\ApiResponse\ApiResponseFactory;
use Tocda\Infrastructure\Controller\AbstractTocdaController;
use Tocda\Message\Command\User\DeleteUserCommand;

#[AsController]
class DeleteUser extends AbstractTocdaController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route(path: '/api/user/{id}', name: 'tocda_api_delete_user', methods: ['DELETE'])]
    public function __invoke(Request $request, MessageBusInterface $commandBus): Response
    {
        /** @var string $id */
        $id = $request->get('id');

        $commandBus->dispatch(
            new DeleteUserCommand(
                id: $id
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La demande a été prise en compte.']);
    }
}