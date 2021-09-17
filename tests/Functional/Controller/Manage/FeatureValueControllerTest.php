<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Root;

use App\Tests\DataFixtures\Controller\Root\ProjectControllerFixture;
use App\Tests\Functional\Controller\AbstractControllerTest;

class FeatureValueControllerTest extends AbstractControllerTest
{
    public function testSetFeatureValue(): void
    {

    }

    protected function getFixtures(): array
    {
        return [
            ProjectControllerFixture::class,
        ];
    }
}
