<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class EmptyFixture extends AbstractFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $objectManager): void
    {
    }
}
