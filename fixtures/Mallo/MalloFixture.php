<?php

declare(strict_types=1);

namespace Tocda\Fixtures\Mallo;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Tocda\Fixtures\AbstractFixture;
use Tocda\Tests\Faker\Entity\Mallo\MalloFaker;

final class MalloFixture extends AbstractFixture implements FixtureGroupInterface
{
    protected function supply(ObjectManager $manager): iterable
    {
        yield MalloFaker::new();
    }

    public static function getGroups(): array
    {
        return ['mallo'];
    }
}
