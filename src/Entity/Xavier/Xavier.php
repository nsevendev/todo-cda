<?php

declare(strict_types=1);

namespace Tocda\Entity\Xavier;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Tocda\Repository\Xavier\XavierRepository;

#[ORM\Entity(repositoryClass: XavierRepository::class)]
class Xavier
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    public function id(): Uuid
    {
        return $this->id;
    }

    #[ORM\Column(type: 'string', name: 'firstname', nullable: false, length: 25)]
    private string $firstname;

    public function firstname(): string
    {
        return $this->firstname;
    }

    #[ORM\Column(type: 'string', name: 'lastname', nullable: false, length: 25)]
    private string $lastname;

    public function lastname(): string
    {
        return $this->lastname;
    }

    #[ORM\Column(type: 'int', name: 'number', nullable: false)]
    private int $number;

    public function number(): int
    {
        return $this->number;
    }

    #[ORM\Column(type: 'datetime_immutable', name: 'created_at', nullable: false)]
    private DateTimeImmutable $createdAt;

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\Column(type: 'datetime_immutable', name: 'updated_at', nullable: false)]
    private DateTimeImmutable $updatedAt;

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function __construct(
        string $firstname,
        string $lastname,
        int $number,
    ) {
        $this->id = Uuid::v7();
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->number = $number;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }
}
