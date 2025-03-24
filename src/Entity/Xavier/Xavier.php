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

    #[ORM\Column(type: 'string', name: 'firstname', nullable: false)]
    private int $firstname;

    public function firstname(): int
    {
        return $this->firstname;
    }

    #[ORM\Column(type: 'string', name: 'lastname', nullable: false, length: 255)]
    private string $lastname;

    public function lastname(): string
    {
        return $this->lastname;
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
        int $status,
        string $message,
    ) {
        $this->id = Uuid::v7();
        $this->firstname = $firstname;
        $this->lastname = $mlastname;
        $this->number = $number;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }
}
