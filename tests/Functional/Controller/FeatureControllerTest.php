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
        $this->authorizeWithReadAccessToken(FeatureControllerFixture::DEMO_READ_KEY);
        $this->client->request(Request::METHOD_GET, '/feature/antonshell/demo/feature1/prod');
        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'status' => Response::HTTP_OK,
            'feature' => 'feature1',
            'environment' => 'prod',
            'enabled' => true
        ], $content);
    }

    public function testGetFeatureFlagWrongProject(): void
    {
        $this->authorizeWithReadAccessToken(FeatureControllerFixture::DEMO_READ_KEY);
        $this->client->request(Request::METHOD_GET, '/feature/antonshell/wrong_project/feature1/prod');
        self::assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'status' => Response::HTTP_FORBIDDEN,
            'message' => 'Project antonshell/wrong_project not found',
        ], $content);
    }

    public function testGetFeatureFlagWrongReadToken(): void
    {
        $this->authorizeWithReadAccessToken('wrong_read_key');
        $this->client->request(Request::METHOD_GET, '/feature/antonshell/demo/feature1/prod');
        self::assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'status' => Response::HTTP_FORBIDDEN,
            'message' => 'Invalid access token provided',
        ], $content);
    }

    public function testGetFeatureFlagWrongEnvironment(): void
    {
        $this->authorizeWithReadAccessToken(FeatureControllerFixture::DEMO_READ_KEY);
        $this->client->request(Request::METHOD_GET, '/feature/antonshell/demo/feature1/wrong_environment');
        self::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'status' => Response::HTTP_NOT_FOUND,
            'message' => 'Environment not found: wrong_environment',
        ], $content);
    }

    public function testGetFeatureFlagNotExists(): void
    {
        $this->authorizeWithReadAccessToken(FeatureControllerFixture::DEMO_READ_KEY);
        $this->client->request(Request::METHOD_GET, '/feature/antonshell/demo/wrong_feature/prod');
        self::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'status' => Response::HTTP_NOT_FOUND,
            'message' => 'Feature not found: wrong_feature',
        ], $content);
    }

    public function testGetFeatureFlagMissingValue(): void
    {
        $this->authorizeWithReadAccessToken(FeatureControllerFixture::PROJECT2_READ_KEY);
        $this->client->request(Request::METHOD_GET, '/feature/antonshell/project2/feature2/prod');
        self::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'status' => Response::HTTP_NOT_FOUND,
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
