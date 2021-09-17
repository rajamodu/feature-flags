<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repository;

use App\Tests\DataFixtures\EmptyFixture;
use App\Tests\Functional\FixtureWebTestCase;

class FeatureValueRepositoryTest extends FixtureWebTestCase
{
    public function testFindOneByFeatureAndEnvironment(): void
    {

    }

    protected function getFixtures(): array
    {
        return [
            EmptyFixture::class,
        ];
    }
}
