<?php

declare(strict_types=1);

namespace Tocda\Message\Command\Ping;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Tocda\Entity\Ping\Dto\PingPublishDeletedDto;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Ping\PingBadRequestException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\Mercure\MercurePublish;
use Tocda\Repository\Ping\PingRepository;

#[AsMessageHandler(bus: 'command.bus')]
class DeletePingHandler
{
    public function __construct(private readonly PingRepository $pingRepository, private readonly MercurePublish $mercurePublish) {}

    /**
     * @return void|null
     *
     * @throws MercureInvalidArgumentException
     * @throws PingBadRequestException
     */
    public function __invoke(DeletePingCommand $command)
    {
        $ping = $this->pingRepository->find($command->id);

        if (null === $ping) {
            throw new PingBadRequestException(errors: [Error::create('ping', "Aucun ping n'a été trouvé")]);
        }

        $pingDto = PingPublishDeletedDto::fromArray($ping);

        $this->pingRepository->remove($ping);

        $this->mercurePublish->publish(
            topic: '/ping-deleted',
            data: $pingDto->toArray()
        );
    }
}
