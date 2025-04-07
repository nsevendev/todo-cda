<?php

declare(strict_types=1);

namespace Tocda\Controller\Api\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;   
use Tocda\Entity\User\Dto\UserCreateDto;
use Tocda\Infrastructure\ApiResponse\ApiResponseFactory;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\User\UserInvalidArgumentException;
use Tocda\Infrastructure\Controller\AbstractTocdaController;
use Tocda\Message\Command\User\CreateUserCommand;

#[AsController]
class CreateUser extends AbstractTocdaController
{
    /** 
     * @throws ExceptionInterface
     * @throws UserInvalidArgumentException
     * @throws Throwable
     */
    #[Route(path: '/api/user', name: 'tocda_api_create_user', methods: ['POST'])]
    public function __invoke(
        Request $request,
        MessageBusInterface $commandBus,
    ) : Response {
        /** @var UserCreateDto */
        $dto= $this->deserializeAndValidate(
            data: $request->getContent(),
            dtoClass: UserCreateDto::class,
            fnException: fn (array $errors) => new UserInvalidArgumentException(
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch(
            new CreateUserCommand(
                userEntityCreateDto: $dto
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La demande a été prise en compte.']); 
    }
}
