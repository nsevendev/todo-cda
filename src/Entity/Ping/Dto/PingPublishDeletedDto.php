<?php

declare(strict_types=1);

namespace Tocda\Entity\Ping\Dto;

use Tocda\Entity\Ping\Ping;

class PingPublishDeletedDto
{
    public function __construct(
        public string $id,
        public int $status,
        public string $message,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromArray(Ping $data): self
    {
        return new self(
            id: (string) $data->id(),
            status: $data->status()->value(),
            message: $data->message()->value(),
            createdAt: $data->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $data->updatedAt()->format('Y-m-d H:i:s'),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'message' => $this->message,
            'delete' => 'true',
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
