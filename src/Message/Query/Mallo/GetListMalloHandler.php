<?php

declare(strict_types=1);

namespace Tocda\Message\Query\Mallo;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Tocda\Entity\Mallo\Dto\MalloDto;
use Tocda\Entity\Mallo\Mallo;
use Tocda\Repository\Mallo\MalloRepository;

#[AsMessageHandler]
class GetListMalloHandler
{
    public function __construct(private MalloRepository $malloRepository) {}

    /**
     * @return MalloDto[]
     */
    public function __invoke(GetListMalloQuery $query): array
    {
        /** @var Mallo[] $listMallo */
        $listMallo = $this->malloRepository->findAll();

        return MalloDto::toListMallo($listMallo);
    }
}
