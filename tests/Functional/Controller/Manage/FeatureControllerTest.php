<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Root;

use App\Tests\DataFixtures\Controller\Manage\EnvironmentControllerFixture;
use App\Tests\DataFixtures\Controller\Manage\FeatureControllerFixture;
use App\Tests\Functional\Controller\AbstractControllerTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FeatureControllerTest extends AbstractControllerTest
{
    public function testGetProjectFeatures(): void
    {
        $this->authorizeWithManageAccessToken();
        $this->client->request(Request::METHOD_GET, '/api/features');
        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            [
                'id' => 1,
                'name' => 'feature1',
                'description' => 'feature 1',
            ],
            [
                'id' => 2,
                'name' => 'feature3',
                'description' => 'feature 3',
            ],
        ], $content);
    }

    public function testGetByName(): void
    {
        $this->authorizeWithManageAccessToken();
        $this->client->request(Request::METHOD_GET, '/api/feature/feature1');
        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'id' => 1,
            'name' => 'feature1',
            'description' => 'feature 1',
            'values' => [
                'prod' => true,
                'stage' => true,
            ],
        ], $content);
    }

    public function testGetByNameNotFound(): void
    {
        $this->authorizeWithManageAccessToken();
        $this->client->request(Request::METHOD_GET, '/api/feature/wrong_feature');
        self::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testCreate(): void
    {
        $this->authorizeWithManageAccessToken();
        $this->sendPostApiRequest(sprintf('/api/feature'), [
            'name' => 'feature2',
            'description' => 'feature 2',
        ]);
        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'id' => 4,
            'name' => 'feature2',
            'description' => 'feature 2',
            'values' => [
                'prod' => true,
                'stage' => true,
            ],
        ], $content);
    }

    public function testCreateDuplicate(): void
    {
        $this->authorizeWithManageAccessToken();
        $this->sendPostApiRequest(sprintf('/api/feature'), [
            'name' => 'feature1',
            'description' => 'feature 4',
        ]);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateInvalid(): void
    {
        $this->authorizeWithManageAccessToken();
        $this->sendPostApiRequest(sprintf('/api/feature'), [
            'name' => '',
            'description' => 'Feature 4',
        ]);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdate(): void
    {
        $this->authorizeWithManageAccessToken();
        $this->sendPostApiRequest('/api/feature/feature1', [
            'name' => 'feature1-new',
            'description' => 'feature1 new',
        ]);
        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals('feature1-new', $content['name']);
        self::assertEquals('feature1 new', $content['description']);
    }

    public function testUpdateNotFound(): void
    {
        $this->authorizeWithManageAccessToken();
        $this->sendPostApiRequest('/api/feature/wrong_feature', [
            'name' => 'feature1-new',
            'description' => 'feature1 new',
        ]);
        self::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateDuplicate(): void
    {
        $this->authorizeWithManageAccessToken();
        $this->sendPostApiRequest('/api/feature/feature1', [
            'name' => 'feature3',
            'description' => 'feature1 new',
        ]);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateInvalid(): void
    {
        $this->authorizeWithManageAccessToken();
        $this->sendPostApiRequest('/api/feature/feature1', [
            'name' => '',
            'description' => 'feature1 new',
        ]);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testDelete(): void
    {
        $this->authorizeWithManageAccessToken();
        $this->client->request(Request::METHOD_DELETE, '/api/feature/feature1');
        self::assertEquals(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteNotFound(): void
    {
        $this->authorizeWithManageAccessToken();
        $this->client->request(Request::METHOD_DELETE, '/api/feature/wrong_feature');
        self::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    protected function getFixtures(): array
    {
        return [
            FeatureControllerFixture::class,
        ];
    }
}
