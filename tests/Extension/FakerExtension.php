<?php

declare(strict_types=1);

namespace Tocda\Tests\Extension;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use Tocda\Tests\Extension\Event\FixedRandomSeedHandler;

final class FakerExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $facade->registerSubscribers(
            new FixedRandomSeedHandler(),
        );
    }
}
