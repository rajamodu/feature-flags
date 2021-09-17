<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Tests\DataFixtures\Controller\WebsiteFixture;
use App\Tests\Functional\FixtureWebTestCase;

abstract class AbstractControllerTest extends FixtureWebTestCase
{
    protected function authorizeWithReadAccessToken(string $token = 'demo_read_key'): void
    {
        // @TODO add token to request
        $_SERVER['HTTP_AUTHORIZATION'] = sprintf('bearer %s', $token);
    }

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * {@inheritdoc}
     */
    protected function getFixtures(): array
    {
        return [
            WebsiteFixture::class,
        ];
    }
}
