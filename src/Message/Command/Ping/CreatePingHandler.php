<?php

declare(strict_types=1);

namespace Tocda\Message\Command\Ping;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Tocda\Entity\Ping\Dto\PingDto;
use Tocda\Entity\Ping\Ping;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Tocda\Infrastructure\Mercure\MercurePublish;
use Tocda\Repository\Ping\PingRepository;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreatePingHandler
{
    public function __construct(
        private PingRepository $pingEntityRepository,
        private MercurePublish $mercurePublish,
    ) {}

    /**
     * @throws MercureInvalidArgumentException
     */
    public function __invoke(CreatePingCommand $command): void
    {
        $ping = new Ping(
            status: $command->pingEntityCreateDto->status()->value(),
            message: $command->pingEntityCreateDto->message()->value()
        );

        $this->pingEntityRepository->save(
            ping: $ping
        );

        $pingDto = PingDto::fromArray($ping);

        $this->mercurePublish->publish(
            topic: '/ping-created',
            data: $pingDto->toArray()
        );
    }
}
