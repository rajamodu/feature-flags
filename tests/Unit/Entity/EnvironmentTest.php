<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Environment;
use App\Entity\Project;
use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $environment = new Environment();
        $project = new Project();
        $environment->setProject($project);
        self::assertEquals($project, $environment->getProject());
    }
}
