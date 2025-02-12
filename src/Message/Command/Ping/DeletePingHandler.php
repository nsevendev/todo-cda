<?php

declare(strict_types=1);

namespace Tocda\Message\Command\Ping;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Tocda\Repository\Ping\PingRepository;

#[AsMessageHandler(bus: 'command.bus')]
class DeletePingHandler
{
    public function __construct(private PingRepository $pingRepository) {}

    public function __invoke(DeletePingCommand $command): void
    {
        $this->pingRepository->remove($command->id);
    }
}
