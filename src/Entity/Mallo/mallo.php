<?php

declare(strict_types=1); // Déclare le typage strict

namespace Tocda\Entity\Mallo; // Définie le namespace de la classe Mallo

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Tocda\Entity\Mallo\ValueObject\MalloFirstname;
use Tocda\Entity\Mallo\ValueObject\MalloLastname;
use Tocda\Entity\Mallo\ValueObject\MalloNumber;
use Tocda\Repository\Mallo\MalloRepository;

#[ORM\Entity(repositoryClass: MalloRepository::class)] // Balise la classe Mallo comme étant une entity lié au repo Mallo pour Doctrine
class Mallo // Définie la classe Mallo
{
    #[ORM\Id] // Définie l'identifiant comme étant la clé primaire de l'entity Mallo
    #[ORM\Column(type: 'uuid', unique: true)] // Balise la propriété id comme étant une colonne de l'entity Mallo puis lui attribue certains paramètres comme le fait qu'il soit unique et son type
    private Uuid $id; // Définie la propriété id de la classe Mallo

    #[ORM\Column(type: 'datetime_immutable', name: 'created_at', nullable: false)] // Balise la colonne created_at
    private DateTimeImmutable $createdAt; // Définie la propriété createdAt de la classe Mallo

    #[ORM\Column(type: 'datetime_immutable', name: 'updated_at', nullable: false)] // Balise la colonne updated_at
    private DateTimeImmutable $updatedAt; // Définie la propriété updatedAt de la classe Mallo

    public function __construct(// Initialiser les propriétés de la classe Mallo
        #[ORM\Column(name: 'firstname', type: 'app_mallo_firstname', nullable: false, length: 25)] // Balise la colonne firstname
        private MalloFirstname $firstname, // Définie la propriété firstname de la classe Mallo
        #[ORM\Column(type: 'app_mallo_lastname', name: 'lastname', nullable: false, length: 25)]
        private MalloLastname $lastname,
        #[ORM\Column(type: 'app_mallo_number', name: 'number', nullable: false)]
        private MalloNumber $number,
    ) {
        $this->id = Uuid::v7(); // Initialise la propriété id de la classe Mallo en lui donnant la valeur de la méthode v7 de la classe Uuid
        $this->createdAt = new DateTimeImmutable(); // C'est la propriété createdAt de la classe Mallo; elle est initialisée à la date et l'heure actuelle
        $this->updatedAt = $this->createdAt; //  C'est la propriété updateAt de la classe Mallo qui est égal à la propriété createdAt de la classe Mallo
    }

    public function id(): Uuid // Définie la méthode id de la classe Mallo (méthode de la classe Mallo)
    {
        return $this->id; // Retourne la propriété id de la classe Mallo
    }

    public function firstname(): MalloFirstname // Définie la méthode firstname de la classe Mallo
    {
        return $this->firstname; // Retourne la propriété firstname de la classe Mallo
    }

    public function lastname(): MalloLastname // Définie la méthode lastname de la classe Mallo
    {
        return $this->lastname; // Retourne la propriété lastname de la classe Mallo
    }

    public function number(): MalloNumber // Définie la méthode number de la classe Mallo
    {
        return $this->number; // Retourne la propriété number de la classe Mallo
    }

    public function createdAt(): DateTimeImmutable // Définie la méthode createdAt de la classe Mallo
    {
        return $this->createdAt; // Retourne la propriété createdAt de la classe Mallo
    }

    public function updatedAt(): DateTimeImmutable // Définie la méthode updatedAt de la classe Mallo
    {
        return $this->updatedAt; // Retourne la propriété updatedAt de la classe Mallo
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void // Définie la méthode setUpdatedAt de la classe Mallo
    {
        // Propriété
        $this->updatedAt = $updatedAt; // = argument, Modifie/Set la propriété updatedAt de la classe Mallo avec l'argument de la méthode
    }
}
