<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repository;

use App\Tests\DataFixtures\EmptyFixture;
use App\Tests\Functional\FixtureWebTestCase;

class AbstractRepositoryTest extends FixtureWebTestCase
{
    public function testSave(): void
    {

    }

    public function testRemove(): void
    {

    }

    public function testPersist(): void
    {

    }

    public function testFlush(): void
    {

    }

    protected function getFixtures(): array
    {
        return [
            EmptyFixture::class,
        ];
    }
}
