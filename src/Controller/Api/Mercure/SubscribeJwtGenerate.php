<?php

declare(strict_types=1);

namespace Tocda\Controller\Api\Mercure;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Tocda\Infrastructure\ApiResponse\ApiResponseFactory;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Tocda\Infrastructure\Controller\AbstractTocdaController;
use Tocda\Infrastructure\Mercure\MercureJwtGenerator;

#[AsController]
class SubscribeJwtGenerate extends AbstractTocdaController
{
    /**
     * @throws MercureInvalidArgumentException
     */
    #[Route('/api/mercure/jwt/sub', name: 'api_mercure_jwt_sub', methods: ['GET'])]
    public function __invoke(MercureJwtGenerator $mercureJwtGenerator): Response
    {
        return ApiResponseFactory::success(data: [
            'tokenMercureSubscribe' => $mercureJwtGenerator->generateSubscriberToken(),
        ]);
    }
}
