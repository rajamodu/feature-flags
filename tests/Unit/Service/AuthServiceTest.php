<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Service\AuthService;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    private AuthService $authService;
    private ProjectRepository $projectRepository;

    protected function setUp(): void
    {
        $this->projectRepository
            = $this->getMockBuilder(ProjectRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->authService = new AuthService(
            $this->projectRepository,
            ''
        );
    }

    protected function tearDown(): void
    {
        unset(
            $this->authService,
            $this->projectRepository
        );
    }

    public function testVerifyReadAccessTokenMissing(): void
    {
        unset($_SERVER['HTTP_AUTHORIZATION']);
        $project = new Project();
        $project->setReadKey('');
        self::assertFalse($this->authService->verifyReadAccessToken('', $project));
    }

    public function testGetTokenFromGlobalsMissing(): void
    {
        unset($_SERVER['HTTP_AUTHORIZATION']);
        self::assertNull($this->authService->getTokenFromGlobals());
    }
}
