<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Root;

use App\Tests\DataFixtures\Controller\Manage\EnvironmentControllerFixture;
use App\Tests\Functional\Controller\AbstractControllerTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EnvironmentControllerTest extends AbstractControllerTest
{
    public function testGetProjectEnvironments(): void
    {
        $this->authorizeWithReadAccessToken(EnvironmentControllerFixture::DEMO_MANAGE_KEY);
        $this->client->request(Request::METHOD_GET, '/api/environments');
        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            [
                'id' => 1,
                'name' => 'prod',
                'description' => 'Production environment',
            ],
            [
                'id' => 2,
                'name' => 'stage',
                'description' => 'Staging environment',
            ]
        ], $content);
    }

    public function testGetByName(): void
    {
        $this->authorizeWithReadAccessToken(EnvironmentControllerFixture::DEMO_MANAGE_KEY);
        $this->client->request(Request::METHOD_GET, '/api/environment/prod');
        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'id' => 1,
            'name' => 'prod',
            'description' => 'Production environment',
            'features' => [
                'feature1' => true,
            ],
        ], $content);
    }

    public function testGetByNameNotFound(): void
    {
        $this->authorizeWithReadAccessToken(EnvironmentControllerFixture::DEMO_MANAGE_KEY);
        $this->client->request(Request::METHOD_GET, '/api/environment/wrong_environment');
        self::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testCreate(): void
    {
        $this->authorizeWithReadAccessToken(EnvironmentControllerFixture::DEMO_MANAGE_KEY);
        $this->sendPostApiRequest(sprintf('/api/environment'), [
            'name' => 'qa1',
            'description' => 'QA1',
        ]);
        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'id' => 4,
            'name' => 'qa1',
            'description' => 'QA1',
            'features' => [
                'feature1' => true,
            ],
        ], $content);
    }

    public function testCreateDuplicate(): void
    {
        $this->authorizeWithReadAccessToken(EnvironmentControllerFixture::DEMO_MANAGE_KEY);
        $this->sendPostApiRequest(sprintf('/api/environment'), [
            'name' => 'prod',
            'description' => 'prod',
        ]);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateInvalid(): void
    {
        $this->authorizeWithReadAccessToken(EnvironmentControllerFixture::DEMO_MANAGE_KEY);
        $this->sendPostApiRequest(sprintf('/api/environment'), [
            'name' => '',
            'description' => 'QA1',
        ]);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdate(): void
    {
        $this->authorizeWithReadAccessToken(EnvironmentControllerFixture::DEMO_MANAGE_KEY);
        $this->sendPostApiRequest('/api/environment/stage', [
            'name' => 'stage-new',
            'description' => 'stage new',
        ]);
        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals('stage-new', $content['name']);
        self::assertEquals('stage new', $content['description']);
    }

    public function testUpdateNotFound(): void
    {
        $this->authorizeWithReadAccessToken(EnvironmentControllerFixture::DEMO_MANAGE_KEY);
        $this->sendPostApiRequest('/api/environment/wrong_environment', [
            'name' => 'wrong_environment',
            'description' => 'wrong environment',
        ]);
        self::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateDuplicate(): void
    {
        $this->authorizeWithReadAccessToken(EnvironmentControllerFixture::DEMO_MANAGE_KEY);
        $this->sendPostApiRequest('/api/environment/stage', [
            'name' => 'prod',
            'description' => 'stage new',
        ]);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateInvalid(): void
    {
        $this->authorizeWithReadAccessToken(EnvironmentControllerFixture::DEMO_MANAGE_KEY);
        $this->sendPostApiRequest('/api/environment/stage', [
            'name' => '',
            'description' => 'stage new',
        ]);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testDelete(): void
    {
        $this->authorizeWithReadAccessToken(EnvironmentControllerFixture::DEMO_MANAGE_KEY);
        $this->client->request(Request::METHOD_DELETE, '/api/environment/stage');
        self::assertEquals(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteProd(): void
    {
        $this->authorizeWithReadAccessToken(EnvironmentControllerFixture::DEMO_MANAGE_KEY);
        $this->client->request(Request::METHOD_DELETE, '/api/environment/prod');
        self::assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteNotFound(): void
    {
        $this->authorizeWithReadAccessToken(EnvironmentControllerFixture::DEMO_MANAGE_KEY);
        $this->client->request(Request::METHOD_DELETE, '/api/environment/wrong_environment');
        self::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    protected function getFixtures(): array
    {
        return [
            EnvironmentControllerFixture::class,
        ];
    }
}
