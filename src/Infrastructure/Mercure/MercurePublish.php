<?php

declare(strict_types=1);

namespace Tocda\Infrastructure\Mercure;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Mercure\MercureInvalidArgumentException;
use Tocda\Infrastructure\ApiResponse\Exception\Error\Error;

class MercurePublish
{
    public function __construct(private HubInterface $hub) {}

    /**
     * @param array<string, mixed> $data
     *
     * @throws MercureInvalidArgumentException
     */
    public function publish(string $topic, array $data): void
    {
        $json = json_encode($data);

        if (false === $json) {
            throw new MercureInvalidArgumentException(getMessage: 'Données invalides à publier', errors: [Error::create(key: 'data', message: 'Données invalides à publier')]);
        }

        $update = new Update(
            topics: $topic,
            data: $json,
            private: true
        );
        $this->hub->publish($update);
    }
}
