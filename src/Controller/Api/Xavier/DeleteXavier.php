<?php

declare(strict_types=1);

namespace Tocda\Controller\Api\Xavier;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Tocda\Infrastructure\ApiResponse\ApiResponseFactory;
use Tocda\Infrastructure\Controller\AbstractTocdaController;
use Tocda\Message\Command\Xavier\DeleteXavierCommand;

#[AsController]
class DeleteXavier extends AbstractTocdaController
{
    #[Route(path: '/api/xavier/{id}', name: 'tocda_api_delete_xavier', methods: ['DELETE'])]
    public function __invoke(Request $request, MessageBusInterface $commandBus): Response
    {
        /** @var string $id */
        $id = $request->get('id');

        $commandBus->dispatch(
            new DeleteXavierCommand(
                id: $id
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La demande a été prise en compte.']);
    }
}
