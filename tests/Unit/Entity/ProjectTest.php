<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Project;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $project = new Project();
        self::assertNull($project->getUpdatedAt());

        $dateTime = new \DateTime();
        $project->setUpdatedAt($dateTime);
        self::assertEquals($dateTime, $project->getUpdatedAt());
    }
}
