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

    }

    public function testCreateDuplicate(): void
    {

    }

    public function testCreateInvalid(): void
    {

    }

    public function testUpdate(): void
    {

    }

    public function testUpdateNotFound(): void
    {

    }

    public function testUpdateDuplicate(): void
    {

    }

    public function testUpdateInvalid(): void
    {

    }

    public function testDelete(): void
    {
        $this->authorizeWithReadAccessToken(EnvironmentControllerFixture::DEMO_MANAGE_KEY);
        $this->client->request(Request::METHOD_DELETE, '/api/environment/stage');
        self::assertEquals(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());
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
