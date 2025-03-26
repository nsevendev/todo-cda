<?php

declare(strict_types=1);

namespace Tocda\Message\Command\Ping;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Tocda\Entity\Ping\Dto\PingDto;
use Tocda\Entity\Ping\Ping;
use Tocda\Entity\Ping\ValueObject\PingMessage;
use Tocda\Entity\Ping\ValueObject\PingStatus;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Ping\PingInvalidArgumentException;
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
     * @throws PingInvalidArgumentException
     */
    public function __invoke(CreatePingCommand $command): void
    {
        $ping = new Ping(
            status: PingStatus::fromValue($command->pingEntityCreateDto->status),
            message: PingMessage::fromValue($command->pingEntityCreateDto->message)
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
