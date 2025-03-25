<?php

declare(strict_types=1);

namespace Tocda\Entity\Xavier\Dto;

use Tocda\Entity\Xavier\Xavier;

class XavierDto
{
    public function __construct(
        public string $id,
        public string $firstname,
        public string $lastname,
        public int $number,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromArray(Xavier $data): self
    {
        return new self(
            id: (string) $data->id(),
            firstname: $data->firstname(),
            lastname: $data->lastname(),
            number: $data->number(),
            createdAt: $data->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $data->updatedAt()->format('Y-m-d H:i:s'),
        );
    }

    /**
     * @param Xavier[] $data
     *
     * @return XavierDto[]
     */
    public static function toListXavier(array $data): array
    {
        $listXavier = [];

        foreach ($data as $xavier) {
            $listXavier[] = self::fromArray($xavier);
        }

        return $listXavier;
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
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
