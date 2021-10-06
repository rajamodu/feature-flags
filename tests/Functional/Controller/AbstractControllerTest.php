<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Tests\DataFixtures\Controller\Manage\EnvironmentControllerFixture;
use App\Tests\DataFixtures\EmptyFixture;
use App\Tests\Functional\FixtureWebTestCase;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractControllerTest extends FixtureWebTestCase
{
    protected function authorizeWithReadAccessToken(string $token): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = sprintf('bearer %s', $token);
    }

    protected function authorizeWithManageAccessToken(
        string $token = EnvironmentControllerFixture::DEMO_MANAGE_KEY,
        string $owner = EnvironmentControllerFixture::OWNER,
        string $projectName = EnvironmentControllerFixture::DEMO_PROJECT
    ): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = sprintf('bearer %s', $token);
        $_SERVER['HTTP_PROJECT'] = sprintf('%s/%s', $owner, $projectName);
    }

    protected function sendPostApiRequest(string $url, array $body): void
    {
        $headers = ['CONTENT_TYPE' => 'application/json'];
        $this->client->request(Request::METHOD_POST, $url, [], [], $headers, json_encode($body));
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
            EmptyFixture::class,
        ];
    }
}
