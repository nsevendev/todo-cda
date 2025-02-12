<?php

declare(strict_types=1);

namespace Tocda\Entity\Ping\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Tocda\Entity\Ping\ValueObject\PingMessage;
use Tocda\Entity\Ping\ValueObject\PingStatus;

readonly class PingCreateDto
{
    public function __construct(
        #[Assert\Valid]
        private PingStatus $status,
        #[Assert\Valid]
        private PingMessage $message,
    ) {}

    public static function new(int $status, string $message): self
    {
        return new self(
            status: PingStatus::fromValue($status),
            message: PingMessage::fromValue($message),
        );
    }

    public function status(): PingStatus
    {
        return $this->status;
    }

    public function message(): PingMessage
    {
        return $this->message;
    }
}
