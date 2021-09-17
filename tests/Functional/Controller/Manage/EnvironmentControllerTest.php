<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Root;

use App\Tests\DataFixtures\Controller\Root\ProjectControllerFixture;
use App\Tests\Functional\Controller\AbstractControllerTest;

class EnvironmentControllerTest extends AbstractControllerTest
{
    public function testGetProjectEnvironments(): void
    {

    }

    public function testGetByName(): void
    {

    }

    public function testGetByNameNotFound(): void
    {

    }

    public function testCreate(): void
    {

    }

    public function testCreateDuplicate(): void
    {

    }

    public function testCreateInvalid(): void
    {

    }

    public function testUpdate(): void
    {

    }

    public function testUpdateNotFound(): void
    {

    }

    public function testUpdateDuplicate(): void
    {

    }

    public function testUpdateInvalid(): void
    {

    }

    public function testDelete(): void
    {

    }

    public function testDeleteNotFound(): void
    {

    }

    protected function getFixtures(): array
    {
        return [
            ProjectControllerFixture::class,
        ];
    }
}
