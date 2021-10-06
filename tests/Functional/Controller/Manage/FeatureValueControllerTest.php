<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Root;

use App\Tests\DataFixtures\Controller\Manage\EnvironmentControllerFixture;
use App\Tests\DataFixtures\Controller\Manage\FeatureValueControllerFixture;
use App\Tests\Functional\Controller\AbstractControllerTest;
use Symfony\Component\HttpFoundation\Response;

class FeatureValueControllerTest extends AbstractControllerTest
{
    public function testSetFeatureValue(): void
    {
        $this->authorizeWithManageAccessToken();
        $this->sendPostApiRequest(sprintf('/api/feature/feature1/value'), [
            'enabled' => true,
            'environment' => 'prod',
        ]);
        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'status' => 'ok',
            'feature' => 'feature1',
            'environment' => 'prod',
            'enabled' => true,
        ], $content);
    }

    public function testSetFeatureValueNotFoundFeature(): void
    {
        $this->authorizeWithManageAccessToken();
        $this->sendPostApiRequest(sprintf('/api/feature/wrong_feature/value'), [
            'enabled' => true,
            'environment' => 'prod',
        ]);
        self::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testSetFeatureValueNotFoundEnvironment(): void
    {
        $this->authorizeWithManageAccessToken();
        $this->sendPostApiRequest(sprintf('/api/feature/feature1/value'), [
            'enabled' => true,
            'environment' => 'wrong_environment',
        ]);
        self::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testSetFeatureValueNotFoundValue(): void
    {
        $this->authorizeWithManageAccessToken(FeatureValueControllerFixture::PROJECT2_MANAGE_KEY, EnvironmentControllerFixture::OWNER, EnvironmentControllerFixture::DEMO_PROJECT);
        $this->sendPostApiRequest(sprintf('/api/feature/feature2/value'), [
            'enabled' => true,
            'environment' => 'prod',
        ]);
        self::assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
    }

    public function testSetFeatureValueInvalid(): void
    {
        $this->authorizeWithManageAccessToken();
        $this->sendPostApiRequest(sprintf('/api/feature/feature1/value'), [
            'enabled' => '',
            'environment' => 'prod',
        ]);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    protected function getFixtures(): array
    {
        return [
            FeatureValueControllerFixture::class,
        ];
    }
}
