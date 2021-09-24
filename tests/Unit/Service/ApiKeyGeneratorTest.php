<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\ApiKeyGenerator;
use PHPUnit\Framework\TestCase;

class ApiKeyGeneratorTest extends TestCase
{
    private ApiKeyGenerator $apiKeyGenerator;

    protected function setUp(): void
    {
        $this->apiKeyGenerator = new ApiKeyGenerator();
    }

    protected function tearDown(): void
    {
        unset($this->apiKeyGenerator);
    }

    public function testGenerateApiKey(): void
    {
        self::assertEquals(128, strlen($this->apiKeyGenerator->generateApiKey()));
    }
}
