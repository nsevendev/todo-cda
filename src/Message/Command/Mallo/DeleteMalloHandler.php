<?php

declare(strict_types=1);

namespace Tocda\Message\Command\Mallo;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Tocda\Entity\Mallo\Dto\MalloPublishDeletedDto;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mallo\MalloBadRequestException;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;
use Tocda\Infrastructure\Mercure\MercurePublish;
use Tocda\Repository\Mallo\MalloRepository;

#[AsMessageHandler(bus: 'command.bus')]
class DeleteMalloHandler
{
    public function __construct(private readonly MalloRepository $malloRepository, private readonly MercurePublish $mercurePublish) {}

    /**
     * @return void|null
     *
     * @throws MercureInvalidArgumentException
     * @throws MalloBadRequestException
     */
    public function __invoke(DeleteMalloCommand $command)
    {
        $mallo = $this->malloRepository->find($command->id);

        if (null === $mallo) {
            throw new MalloBadRequestException(errors: [Error::create('mallo', "Aucun mallo n'a été trouvé")]);
        }

        $malloDto = MalloPublishDeletedDto::fromArray($mallo);

        $this->malloRepository->remove($mallo);

        $this->mercurePublish->publish(
            topic: '/mallo-deleted',
            data: $malloDto->toArray()
        );
    }
}
