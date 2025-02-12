<?php

declare(strict_types=1);

namespace Tocda\Controller\Api\Ping;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tocda\Infrastructure\ApiResponse\ApiResponseFactory;
use Tocda\Infrastructure\Controller\AbstractTocdaController;
use Tocda\Infrastructure\Serializer\TocdaSerializer;
use Tocda\Message\Query\Ping\GetListPingQuery;

#[AsController]
class ListPing extends AbstractTocdaController
{
    use HandleTrait;

    public function __construct(
        TocdaSerializer $serializer,
        ValidatorInterface $validator,
        /** @phpstan-ignore-next-line */
        private MessageBusInterface $messageBus,
    ) {
        parent::__construct($serializer, $validator);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route(path: '/api/list-ping', name: 'tocda_api_list_ping', methods: ['GET'])]
    public function __invoke(
        Request $request,
    ): Response {
        $listPing = $this->handle(new GetListPingQuery());

        return ApiResponseFactory::success(data: $listPing);
    }
}
