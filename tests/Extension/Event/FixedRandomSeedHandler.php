<?php

declare(strict_types=1);

namespace Tocda\Tests\Extension\Event;

use PHPUnit\Event\Test\PreparationStarted;
use PHPUnit\Event\Test\PreparationStartedSubscriber;

final class FixedRandomSeedHandler implements PreparationStartedSubscriber
{
    /**
     * This subscriber has a role that is directly bound to the usage of Faker:
     * it will be executed for every PHPUnit test, before the test is actually
     * run. The "seed" for the random functions will be re-assigned for every
     * test, based on the signature of the test, guarantying that the seed will
     * be the same for each test, every time they run. This leads to random
     * values being generated by Faker being the exact same values, as long as
     * the test signature doesn't change.
     *
     * @see \Faker\Generator::seed()
     */
    public function notify(PreparationStarted $event): void
    {
        $hash = crc32($event->test()->id());

        mt_srand($hash);
    }
}
