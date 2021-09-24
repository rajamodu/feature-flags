<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Feature;
use App\Entity\Project;
use PHPUnit\Framework\TestCase;

class FeatureTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $feature = new Feature();
        $project = new Project();
        $feature->setProject($project);
        self::assertEquals($project, $feature->getProject());
    }
}
