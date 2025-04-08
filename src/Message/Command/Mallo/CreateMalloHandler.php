<?php

declare(strict_types=1);

namespace Tocda\Message\Command\Mallo;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Tocda\Entity\Mallo\Dto\MalloDto;
use Tocda\Entity\Mallo\Mallo;
use Tocda\Entity\Mallo\ValueObject\MalloFirstname;
use Tocda\Entity\Mallo\ValueObject\MalloLastname;
use Tocda\Entity\Mallo\ValueObject\MalloNumber;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mallo\MalloInvalidArgumentException;
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
     * @throws MalloInvalidArgumentException
     */
    public function __invoke(CreateMalloCommand $command): void
    {
        $mallo = new Mallo(
            firstname: MalloFirstname::fromValue($command->malloEntityCreateDto->firstname),
            lastname: MalloLastname::fromValue($command->malloEntityCreateDto->lastname),
            number: MalloNumber::fromValue($command->malloEntityCreateDto->number)
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
