<?php

declare(strict_types=1); // Déclare le typage strict

namespace Tocda\Entity\Mallo; // Définie le namespace de la classe Mallo

// Les imports
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Tocda\Repository\Mallo\MalloRepository;

#[ORM\Entity(repositoryClass: MalloRepository::class)] // Balise la classe Mallo comme étant une entity lié au repo Mallo pour Doctrine
class Mallo // Définie la classe Mallo
{
    #[ORM\Id] // Définie l'identifiant comme étant la clé primaire de l'entity Mallo
    #[ORM\Column(type: 'uuid', unique: true)] // Balise la propriété id comme étant une colonne de l'entity Mallo puis lui attribue certains paramètres comme le fait qu'il soit unique et son type
    private Uuid $id; // Définie la propriété id de la classe Mallo

    public function id(): Uuid // Définie la méthode id de la classe Mallo (méthode de la classe Mallo)
    {
        return $this->id; // Retourne la propriété id de la classe Mallo
    }

    #[ORM\Column(type: 'string', name: 'firstname', nullable: false, length: 25)] // Balise la colonne firstname
    private string $firstname; // Définie la propriété firstname de la classe Mallo

    public function firstname(): string // Définie la méthode firstname de la classe Mallo
    {
        return $this->firstname; // Retourne la propriété firstname de la classe Mallo
    }

    #[ORM\Column(type: 'string', name: 'lastname', nullable: false, length: 25)] // Balise la colonne lastname
    private string $lastname; // Définie la propriété lastname de la classe Mallo

    public function lastname(): string // Définie la méthode lastname de la classe Mallo
    {
        return $this->lastname; // Retourne la propriété lastname de la classe Mallo
    }

    #[ORM\Column(type: 'string', name: 'number', nullable: false)] // Balise la colonne number
    private int $number; // Définie la propriété number de la classe Mallo

    public function number(): int // Définie la méthode number de la classe Mallo
    {
        return $this->number; // Retourne la propriété number de la classe Mallo
    }

    #[ORM\Column(type: 'datetime_immutable', name: 'created_at', nullable: false)] // Balise la colonne created_at
    private DateTimeImmutable $createdAt; // Définie la propriété createdAt de la classe Mallo

    public function createdAt(): DateTimeImmutable // Définie la méthode createdAt de la classe Mallo
    {
        return $this->createdAt; // Retourne la propriété createdAt de la classe Mallo
    }

    #[ORM\Column(type: 'datetime_immutable', name: 'updated_at', nullable: false)] // Balise la colonne updated_at
    private DateTimeImmutable $updatedAt; // Définie la propriété updatedAt de la classe Mallo

    public function updatedAt(): DateTimeImmutable // Définie la méthode updatedAt de la classe Mallo
    {
        return $this->updatedAt; // Retourne la propriété updatedAt de la classe Mallo
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void // Définie la méthode setUpdatedAt de la classe Mallo
    {
        // Propriété
        $this->updatedAt = $updatedAt; // = argument, Modifie/Set la propriété updatedAt de la classe Mallo avec l'argument de la méthode
    }

    public function __construct(// Initialiser les propriétés de la classe Mallo
        string $firstname, // Argument 1
        string $lastname, // Argument 2
        int $number, // Argument 3
    ) {
        $this->id = Uuid::v7(); // Initialise la propriété id de la classe Mallo en lui donnant la valeur de la méthode v7 de la classe Uuid
        $this->firstname = $firstname; // Initialise la propriété firstname de la classe Mallo en lui donnant la valeur de l'argument 1
        $this->lastname = $lastname; // Voir com 77
        $this->number = $number; // Voir com 77
        $this->createdAt = new DateTimeImmutable(); // C'est la propriété createdAt de la classe Mallo; elle est initialisée à la date et l'heure actuelle
        $this->updatedAt = $this->createdAt; //  C'est la propriété updateAt de la classe Mallo qui est égal à la propriété createdAt de la classe Mallo
    }
}
