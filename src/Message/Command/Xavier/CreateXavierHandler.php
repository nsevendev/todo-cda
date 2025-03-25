<?php

declare(strict_types=1);

namespace Tocda\Message\Command\Xavier;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Tocda\Entity\Xavier\Dto\XavierDto;
use Tocda\Entity\Xavier\Xavier;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Tocda\Infrastructure\Mercure\MercurePublish;
use Tocda\Repository\Xavier\XavierRepository;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreateXavierHandler
{
    public function __construct(
        private XavierRepository $xavierEntityRepository,
        private MercurePublish $mercurePublish,
    ) {}

    /**
     * @throws MercureInvalidArgumentException
     */
    public function __invoke(CreateXavierCommand $command): void
    {
        $xavier = new Xavier(
            firstname: $command->xavierEntityCreateDto->firstname()->value(),
            lastname: $command->xavierEntityCreateDto->lastname()->value(),
            number: $command->xavierEntityCreateDto->number()->value()
        );

        $this->xavierEntityRepository->save(
            xavier: $xavier
        );

        $xavierDto = XavierDto::fromArray($xavier);

        $this->mercurePublish->publish(
            topic: '/xavier-created',
            data: $xavierDto->toArray()
        );
    }
}
