<?php

declare(strict_types=1);

namespace Tocda\Message\Query\Xavier;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Tocda\Entity\Xavier\Dto\XavierDto;
use Tocda\Entity\Xavier\Xavier;
use Tocda\Repository\Xavier\XavierRepository;

#[AsMessageHandler]
class GetListXavierHandler
{
    public function __construct(private XavierRepository $xavierRepository) {}

    /**
     * @return XavierDto[]
     */
    public function __invoke(GetListXavierQuery $query): array
    {
        /** @var Xavier[] $listXavier */
        $listXavier = $this->xavierRepository->findAll();

        return XavierDto::toListXavier($listXavier);
    }
}
