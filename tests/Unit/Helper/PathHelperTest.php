<?php

declare(strict_types=1);

namespace App\Tests\Unit\Helper;

use App\Helper\PathHelper;
use PHPUnit\Framework\TestCase;

class PathHelperTest extends TestCase
{
    public function testGetBasePath(): void
    {
        self::assertEquals(dirname(__DIR__, 3), PathHelper::getBasePath());
    }

    public function testGetWebRootPath(): void
    {
        self::assertEquals(dirname(__DIR__, 3) . '/public', PathHelper::getWebRootPath());
    }
}
