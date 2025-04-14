<?php

declare(strict_types=1);

namespace Tocda\Entity\User;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Tocda\Entity\User\ValueObject\UserEmail;
use Tocda\Entity\User\ValueObject\UserPassword;
use Tocda\Entity\User\ValueObject\UserUsername;
use Tocda\Repository\User\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable', nullable: false)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable', nullable: false)]
    private DateTimeImmutable $updatedAt;

    public function __construct(
        #[ORM\Column(name: 'username', type: 'app_user_username', nullable: false, length: 25)]
        public UserUsername $username,
        #[ORM\Column(name: 'email', type: 'app_user_email', nullable: false, length: 255)]
        private UserEmail $email,
        #[ORM\Column(name: 'password', type: 'app_user_password', nullable: false, length: 255)]
        private UserPassword $password,
    ) {
        $this->id = Uuid::v7();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function username(): UserUsername
    {
        return $this->username;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function password(): UserPassword
    {
        return $this->password;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
