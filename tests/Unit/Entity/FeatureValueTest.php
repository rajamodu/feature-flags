<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\FeatureValue;
use PHPUnit\Framework\TestCase;

class FeatureValueTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $featureValue = new FeatureValue();
        self::assertNull($featureValue->getId());
    }
}
