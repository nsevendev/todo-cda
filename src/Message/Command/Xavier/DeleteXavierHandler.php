<?php

declare(strict_types=1);

namespace Tocda\Message\Command\Xavier;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Tocda\Repository\Xavier\XavierRepository;

#[AsMessageHandler(bus: 'command.bus')]
class DeleteXavierHandler
{
    public function __construct(private XavierRepository $xavierRepository) {}

    public function __invoke(DeleteXavierCommand $command): void
    {
        $this->xavierRepository->remove($command->id);
    }
}
