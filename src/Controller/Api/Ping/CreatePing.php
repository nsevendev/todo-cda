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

#[AsController] // #[AsController] est un attribut de classe qui permet de définir une classe comme contrôleur
class CreatePing extends AbstractTocdaController // class CreatePing qui hérite de AbstractTocdaController
{
    /**
     * @throws ExceptionInterface 
     * @throws PingInvalidArgumentException 
     * @throws Throwable
     */
    #[Route(path: '/api/ping', name: 'tocda_api_create_ping', methods: ['POST'])] // methode POST donc Create 
    public function __invoke( // __invoke est une méthode de la classe CreatePing
        Request $request, // Request  
        MessageBusInterface $commandBus, 
    ): Response { // Retourne une instance de la classe Response
        /** @var PingCreateDto $dto */
        $dto = $this->deserializeAndValidate( // -> = appel de la méthode deserializeAndValidate
            data: $request->getContent(), // -> récupère le contenu JSON envoyé dans la requête.
            dtoClass: PingCreateDto::class, // dtoClass est une classe de PingCreateDto
            fnException: fn (array $errors) => new PingInvalidArgumentException( // est une fonction fléchée (fn (...) => ...), qui retourne une PingInvalidArgumentException en cas d’erreurs de validation.
                getMessage: 'Erreur de validation',
                errors: $errors
            )
        );

        $commandBus->dispatch( // Appel de la méthode dispatch de la classe MessageBusInterface methode utilisé pour envoyer une commande au bus de commande
            new CreatePingCommand( 
                pingEntityCreateDto: $dto 
            )
        );

        return ApiResponseFactory::success(data: ['message' => 'La demande a été prise en compte.']); 
    }
}
