<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Root;

use App\Entity\Project;
use App\Tests\DataFixtures\Controller\Root\ProjectControllerFixture;
use App\Tests\Functional\Controller\AbstractControllerTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectControllerTest extends AbstractControllerTest
{
    private const ROOT_TOKEN = 'root_token';

    public function testGetAll(): void
    {
        $this->authorizeWithReadAccessToken(self::ROOT_TOKEN);
        $this->client->request(Request::METHOD_GET, '/manage/projects');
        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        $expectedProject = [
            'id' => 1,
            'name' => 'demo',
            'description' => 'demo project',
            'owner' => 'antonshell',
        ];
        self::assertEquals([$expectedProject], $content);
    }

    public function testGetById(): void
    {
        /** @var Project $project */
        $project = $this->getReference(ProjectControllerFixture::DEMO_PROJECT_REF);
        $this->authorizeWithReadAccessToken(self::ROOT_TOKEN);
        $this->client->request(Request::METHOD_GET, sprintf('/manage/project/%s', $project->getId()));
        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'id' => 1,
            'name' => 'demo',
            'description' => 'demo project',
            'owner' => 'antonshell',
            'read_key' => 'demo_read_key',
            'manage_key' => 'demo_manage_key',
            'environments' => [
                [
                    'name' => 'prod',
                    'description' => 'Production environment',
                ],
            ],
            'features' => [
                [
                    'name' => 'feature1',
                    'description' => 'feature 1',
                    'values' => [
                        'prod' => true,
                    ],
                ],
            ],
        ], $content);
    }

    public function testGetByIdNotFound(): void
    {
        $this->authorizeWithReadAccessToken(self::ROOT_TOKEN);
        $this->client->request(Request::METHOD_GET,'/manage/project/123');
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
        /** @var Project $project */
        $project = $this->getReference(ProjectControllerFixture::DEMO_PROJECT_REF);
        $this->authorizeWithReadAccessToken(self::ROOT_TOKEN);
        $this->sendPostApiRequest(sprintf('/manage/project/%s', $project->getId()), [
            'name' => 'demo-new',
            'description' => 'demo new',
            'owner' => 'new-owner',
        ]);
        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'id' => 1,
            'name' => 'demo-new',
            'description' => 'demo new',
            'owner' => 'new-owner',
            'read_key' => 'demo_read_key',
            'manage_key' => 'demo_manage_key',
            'environments' => [
                [
                    'name' => 'prod',
                    'description' => 'Production environment',
                ],
            ],
            'features' => [
                [
                    'name' => 'feature1',
                    'description' => 'feature 1',
                    'values' => [
                        'prod' => true,
                    ],
                ],
            ],
        ], $content);
    }

    public function testUpdateNotFound(): void
    {
        $this->authorizeWithReadAccessToken(self::ROOT_TOKEN);
        $this->sendPostApiRequest('/manage/project/123', [
            'name' => 'project-123',
            'description' => 'project-123',
            'owner' => 'antonshell',
        ]);
        self::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateInvalid(): void
    {
        /** @var Project $project */
        $project = $this->getReference(ProjectControllerFixture::DEMO_PROJECT_REF);
        $this->authorizeWithReadAccessToken(self::ROOT_TOKEN);
        $this->sendPostApiRequest(sprintf('/manage/project/%s', $project->getId()), [
            'name' => '',
            'description' => 'project-123',
            'owner' => 'antonshell',
        ]);
        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals([
            'status' => Response::HTTP_BAD_REQUEST,
            'message' => 'Validation failed',
            'validation_errors' => [
                'name' => 'This value is too short. It should have 3 characters or more.',
            ],
        ], $content);
    }

    public function testDelete(): void
    {
        /** @var Project $project */
        $project = $this->getReference(ProjectControllerFixture::DEMO_PROJECT_REF);
        $this->authorizeWithReadAccessToken(self::ROOT_TOKEN);
        $this->client->request(Request::METHOD_DELETE, sprintf('/manage/project/%s', $project->getId()));
        self::assertEquals(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteNotFound(): void
    {
        $this->authorizeWithReadAccessToken(self::ROOT_TOKEN);
        $this->client->request(Request::METHOD_DELETE,'/manage/project/123');
        self::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    protected function getFixtures(): array
    {
        return [
            ProjectControllerFixture::class,
        ];
    }
}
