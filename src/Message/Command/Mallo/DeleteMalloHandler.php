<?php

declare(strict_types=1);

namespace Tocda\Message\Command\Mallo;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Tocda\Repository\Mallo\MalloRepository;

#[AsMessageHandler(bus: 'command.bus')]
class DeleteMalloHandler
{
    public function __construct(private MalloRepository $malloRepository) {}

    public function __invoke(DeleteMalloCommand $command): void
    {
        $this->malloRepository->remove($command->id);
    }
}
