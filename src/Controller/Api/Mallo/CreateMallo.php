<?php

declare(strict_types=1);

namespace Tocda\Controller\Api\Mallo;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;
use Tocda\Entity\Mallo\Dto\MalloCreateDto;
use Tocda\Infrastructure\ApiResponse\ApiResponseFactory;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mallo\MalloInvalidArgumentException;
use Tocda\Infrastructure\Controller\AbstractTocdaController;
use Tocda\Message\Command\Mallo\CreateMalloCommand;

#[AsController] // Controller = gerer les requêtes HTTP (GET, POST...) récupère et ou modifie des données en bdd et retourner des réponses JSON
class CreateMallo extends AbstractTocdaController
{
    /**
     * @throws ExceptionInterface
     * @throws MalloInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/mallo', name: 'tocda_api_create_mallo', methods: ['POST'])]
    public function __invoke(
        Request $request,
        MessageBusInterface $commandBus,
    ): Response {
        /** @var MalloCreateDto $dto */
        $dto = $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: MalloCreateDto::class,
            fnException: fn (array $errors) => new MalloInvalidArgumentException(
                firstname: $errors['firstname'] ?? '', // je récupère les erreurs de validation pour chaque champ
                lastname: $errors['lastname'] ?? '',
                number: $errors['number'] ?? '',
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch(
            new CreateMalloCommand(
                malloEntityCreateDto: $dto
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La demande a été prise en compte.']);
    }
}
