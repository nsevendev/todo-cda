<?php

declare(strict_types=1);

namespace Tocda\Message\Query\Ping;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Tocda\Entity\Ping\Dto\PingDto;
use Tocda\Entity\Ping\Ping;
use Tocda\Repository\Ping\PingRepository;

#[AsMessageHandler]
class GetListPingHandler
{
    public function __construct(private PingRepository $pingRepository) {}

    /**
     * @return PingDto[]
     */
    public function __invoke(GetListPingQuery $query): array
    {
        /** @var Ping[] $listPing */
        $listPing = $this->pingRepository->findAll();

        return PingDto::toListPing($listPing);
    }
}
