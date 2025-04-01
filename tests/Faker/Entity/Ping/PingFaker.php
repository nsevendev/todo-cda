<?php

declare(strict_types=1);

namespace Tocda\Tests\Faker\Entity\Ping;

use Tocda\Entity\Ping\Ping;
use Tocda\Entity\Ping\ValueObject\PingMessage;
use Tocda\Entity\Ping\ValueObject\PingStatus;
use Tocda\Infrastructure\ApiResponse\Exception\Custom\Ping\PingInvalidArgumentException;

final class PingFaker
{
    /**
     * @throws PingInvalidArgumentException
     */
    public static function new(): Ping
    {
        return new Ping(
            status: PingStatus::fromValue(200),
            message: PingMessage::fromValue('Le ping à réussi')
        );
    }

    /**
     * @throws PingInvalidArgumentException
     */
    public static function withMessageMoreLonger(): Ping
    {
        return new Ping(
            status: PingStatus::fromValue(200),
            message: PingMessage::fromValue('Le message du ping est trop long Le message du ping est trop long Le message du ping est trop long Le message du ping est trop long Le message du ping est trop long Le message du ping est trop long Le message du ping est trop long Le message du ping est trop long')
        );
    }

    /**
     * @throws PingInvalidArgumentException
     */
    public static function withMessageEmpty(): Ping
    {
        return new Ping(
            status: PingStatus::fromValue(200),
            message: PingMessage::fromValue('')
        );
    }
}
