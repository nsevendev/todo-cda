<?php

declare(strict_types=1);

namespace Tocda\Entity\User\Dto;

use Tocda\Entity\User\User;

class UserDto 
{
    public function __construct(
        public string $id,
        public string $username,
        public string $email,
        public string $password,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromArray(User $data): self 
    {
        return new self(
            id: (string) $data->id(),
            username: $data->username()->value(),
            email: $data->email()->value(),
            password: $data->password()->value(),
            createdAt: $data->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $data->updatedAt()->format('Y-m-d H:i:s'),
        );
    }

    /**
     * @param User[] $data
     *
     * @return UserDto[]
     */
    public static function toListUser(array $data): array
    {
        $listUser = [];

        foreach ($data as $user) {
            $listUser[] = self::fromArray($user);
        }

        return $listUser;
    }
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}