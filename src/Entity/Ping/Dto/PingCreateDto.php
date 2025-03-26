<?php

declare(strict_types=1);

namespace Tocda\Entity\Ping\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Tocda\Entity\Ping\ValueObject\PingMessage;
use Tocda\Entity\Ping\ValueObject\PingStatus;

readonly class PingCreateDto // "readonly" = rend l'élément PingCreateDto non mutable (immutable)
{
    public function __construct(// Constructeur de la class PingCreateDto
        #[Assert\Valid]
        private PingStatus $status,
        #[Assert\Valid]
        private PingMessage $message,
    ) {}

    public static function new(int $status, string $message): self
    {
        return new self(
            status: PingStatus::fromValue($status), // Appelle la méthode fromValue de la classe PingStatus avec $status en paramètre
            message: PingMessage::fromValue($message), // Appelle la méthode fromValue de la classe PingMessage avec $message en paramètre
        );
    }

    public function status(): PingStatus // Fonction qui retourne le status
    {
        return $this->status; // On retourne la valeur status de la class PingCreateDto
    }

    public function message(): PingMessage // Pareil qu'au dessus me fais pas répéter stp
    {
        return $this->message;
    }
}
