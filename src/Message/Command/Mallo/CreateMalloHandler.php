<?php

declare(strict_types=1);

namespace Tocda\Message\Command\Mallo;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Tocda\Entity\Mallo\Dto\MalloDto;
use Tocda\Entity\Mallo\Mallo;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Tocda\Infrastructure\Mercure\MercurePublish;
use Tocda\Repository\Mallo\MalloRepository;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreateMalloHandler
{
    public function __construct(
        private MalloRepository $malloEntityRepository,
        private MercurePublish $mercurePublish,
    ) {}

    /**
     * @throws MercureInvalidArgumentException
     */
    public function __invoke(CreateMalloCommand $command): void
    {
        $mallo = new Mallo(
            firstname: $command->malloEntityCreateDto->firstname()->value(),
            lastname: $command->malloEntityCreateDto->lastname()->value(),
            number: $command->malloEntityCreateDto->number()->value()
        );

        $this->malloEntityRepository->save( // Appel de la méthode save de la classe MalloRepository
            mallo: $mallo
        );

        $malloDto = MalloDto::fromArray($mallo);

        $this->mercurePublish->publish(
            topic: '/mallo-created',
            data: $malloDto->toArray()
        );
    }
}
