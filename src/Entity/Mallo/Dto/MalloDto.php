<?php

declare(strict_types=1);

namespace Tocda\Entity\Mallo\Dto;

use Tocda\Entity\Mallo\Mallo;

class MalloDto // Nouvelle class MalloDto
{
    public function __construct(// Constructeur de la class MalloDto
        public string $id,
        public string $firstname,
        public string $lastname,
        public int $number,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromArray(Mallo $data): self // fromArray = fonction statique de la class Mallo
    {
        return new self( // On retourne un nouveau soi de la class MalloDto
            id: (string) $data->id(), // le string qu'on met pour dire que id est un type string...
            firstname: $data->firstname(),
            lastname: $data->lastname(),
            number: $data->number(),
            createdAt: $data->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $data->updatedAt()->format('Y-m-d H:i:s'),
        );
    }

    /**
     * @param Mallo[] $data
     *
     * @return MalloDto[]
     */
    public static function toListMallo(array $data): array // Fonction statique qui retourne un tableau de PingDto avec $data en argument
    {
        $listMallo = []; // On initialise un tableau vide sous la variable $listMallo

        foreach ($data as $mallo) { // Pour chaque élément de $data
            $listMallo[] = self::fromArray($mallo); // On ajoute à $listMallo un nouvel objet de type MalloDto
        }

        return $listMallo; // On retourne le tableau $listMallo
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array // Fonction qui retourne le tableau
    {
        return [
            'id' => $this->id, // string id => cette valeur de id
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'number' => $this->number,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
