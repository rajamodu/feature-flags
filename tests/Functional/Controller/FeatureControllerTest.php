<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Tests\DataFixtures\Controller\FeatureControllerFixture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FeatureControllerTest extends AbstractControllerTest
{
    public function testGetFeatureFlag(): void
    {
        $this->authorizeWithReadAccessToken();
        $this->client->request(Request::METHOD_GET, '/feature/antonshell/demo/feature1/prod');
        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        // @TODO replace ok to 200
        self::assertEquals([
            'status' => 'ok',
            'feature' => 'feature1',
            'environment' => 'prod',
            'enabled' => true
        ], $content);
    }

    public function testGetFeatureFlagWrongProject(): void
    {
        $this->authorizeWithReadAccessToken();
        $this->client->request(Request::METHOD_GET, '/feature/antonshell/wrong_project/feature1/prod');
        self::assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);

        // @TODO replace 403 to error
        self::assertEquals([
            'status' => 403,
            'message' => 'Project antonshell/wrong_project not found',
        ], $content);
    }

    public function testGetFeatureFlagWrongReadToken(): void
    {
        $this->authorizeWithReadAccessToken('demo_read_key2');
        $this->client->request(Request::METHOD_GET, '/feature/antonshell/demo/feature1/prod');
        self::assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'status' => 403,
            'message' => 'Invalid access token provided',
        ], $content);
    }

    public function testGetFeatureFlagWrongEnvironment(): void
    {
        $this->authorizeWithReadAccessToken();
        $this->client->request(Request::METHOD_GET, '/feature/antonshell/demo/feature1/wrong_environment');
        self::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'status' => 404,
            'message' => 'Environment not found: wrong_environment',
        ], $content);
    }

    public function testGetFeatureFlagNotExists(): void
    {
        $this->authorizeWithReadAccessToken();
        $this->client->request(Request::METHOD_GET, '/feature/antonshell/demo/wrong_feature/prod');
        self::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'status' => 404,
            'message' => 'Feature not found: wrong_feature',
        ], $content);
    }

    public function testGetFeatureFlagMissingValue(): void
    {
        $this->authorizeWithReadAccessToken('demo_read_key2');
        $this->client->request(Request::METHOD_GET, '/feature/antonshell/project2/feature2/prod');
        self::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'status' => 404,
            'message' => 'Feature(2) value is not set for environment(prod)',
        ], $content);
    }

    protected function getFixtures(): array
    {
        return [
            FeatureControllerFixture::class,
        ];
    }
}
