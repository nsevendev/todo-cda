<?php

declare(strict_types=1);

namespace Tocda\Entity\Mallo\Dto;

use Tocda\Entity\Mallo\Mallo;

class MalloPublishDeletedDto
{
    public function __construct(
        public string $id,
        public string $firstname,
        public string $lastname,
        public int $number,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromArray(Mallo $data): self
    {
        return new self(
            id: (string) $data->id(),
            firstname: $data->firstname()->value(),
            lastname: $data->lastname()->value(),
            number: $data->number()->value(),
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
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'number' => $this->number,
            'delete' => 'true',
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
