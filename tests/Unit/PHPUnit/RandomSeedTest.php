<?php

declare(strict_types=1);

namespace Tocda\Tests\Unit\PHPUnit;

use PHPUnit\Framework\Attributes\CoversClass;
use Tocda\Tests\Extension\Event\FixedRandomSeedHandler;
use Tocda\Tests\Unit\TocdaUnitTestCase;

#[CoversClass(FixedRandomSeedHandler::class)]
final class RandomSeedTest extends TocdaUnitTestCase
{
    /**
     * This tests that the random seed is set correctly for each test.
     *
     * @see FixedRandomSeedHandler::notify()
     */
    public function testRandomIntegerIsAlwaysTheSame(): void
    {
        $value = mt_rand();

        self::assertNotSame(3053395, $value);
    }
}
