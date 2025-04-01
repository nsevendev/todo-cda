<?php

declare(strict_types=1);

namespace Tocda\Entity\Ping\Dto;

use Tocda\Entity\Ping\Ping;

class PingDto // Nouvelle class PingDto
{
    public function __construct(// Constructeur de la class PingDto
        public string $id,
        public int $status,
        public string $message,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromArray(Ping $data): self // fromArray = fonction statique de la class Ping ? $data = argument ? (je veux comprendre pcq ça j'ai du mal)
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
     * @param Ping[] $data
     *
     * @return PingDto[]
     */
    public static function toListPing(array $data): array // Fonction statique qui retourne un tableau de PingDto avec $data en argument
    {
        $listPing = []; // On initialise un tableau vide

        foreach ($data as $ping) { // Pour chaque élément de $data on parcours avec foreach
            $listPing[] = self::fromArray($ping); // IDK
        }

        return $listPing; // On retourne le tableau $listPing
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array // Fonction qui retourne le tableau
    {
        return [
            'id' => $this->id, // string id => cette valeur de id
            'status' => $this->status,
            'message' => $this->message,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
