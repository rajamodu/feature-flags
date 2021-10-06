<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Service\AuthService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthServiceTest extends TestCase
{
    private AuthService $authService;
    private ProjectRepository $projectRepository;

    private const READ_KEY_HASH = '$2y$10$PxDki6TfCuRMIS66r7Pobe3BaDRGdOvrcLgap9A2mXpmOlS77IYMm';
    private const MANAGE_KEY_HASH = '$2y$10$c4AIwTaAIUOQzvkBe7aQ1O9I.M/7EkxHCIfMfxuhgiQseIX0OZdd.';

    protected function setUp(): void
    {
        $this->projectRepository
            = $this->getMockBuilder(ProjectRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->authService = new AuthService(
            $this->projectRepository,
            'root_token'
        );
    }

    protected function tearDown(): void
    {
        unset(
            $this->authService,
            $this->projectRepository
        );
    }

    public function testGetProjectByManageKeyAndReference(): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer demo_manage_key';
        $_SERVER['HTTP_PROJECT'] = sprintf('%s/%s', 'antonshell', 'demo');

        $project = new Project();
        $project->setManageKey(self::MANAGE_KEY_HASH);

        $this->projectRepository
            ->expects(self::once())
            ->method('findOneByOwnerAndName')
            ->with('antonshell', 'demo')
            ->willReturn($project)
        ;
        self::assertEquals($project, $this->authService->getProjectByManageKeyAndReference());
    }

    public function testGetProjectByManageKeyAndReferenceMissingReference(): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer demo_manage_key';
        unset($_SERVER['HTTP_PROJECT']);

        $project = new Project();
        $project->setManageKey(self::MANAGE_KEY_HASH);
        $this->expectException(AccessDeniedHttpException::class);
        $this->authService->getProjectByManageKeyAndReference();
    }

    public function testGetProjectByManageKeyAndReferenceInvalidToken(): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer invalid_token';
        $_SERVER['HTTP_PROJECT'] = sprintf('%s/%s', 'antonshell', 'demo');

        $project = new Project();
        $project->setManageKey(self::MANAGE_KEY_HASH);

        $this->projectRepository
            ->expects(self::once())
            ->method('findOneByOwnerAndName')
            ->with('antonshell', 'demo')
            ->willReturn($project)
        ;
        $this->expectException(AccessDeniedHttpException::class);
        $this->authService->getProjectByManageKeyAndReference();
    }

    public function testVerifyReadAccess(): void
    {
        $project = new Project();
        $project->setReadKey(self::READ_KEY_HASH);
        self::assertTrue($this->authService->verifyReadAccessToken('demo_read_key', $project));
    }

    public function testVerifyReadAccessTokenMissing(): void
    {
        $project = new Project();
        $project->setReadKey(self::READ_KEY_HASH);
        self::assertFalse($this->authService->verifyReadAccessToken('', $project));
    }

    public function testVerifyManageAccessToken(): void
    {
        $project = new Project();
        $project->setManageKey(self::MANAGE_KEY_HASH);
        self::assertTrue($this->authService->verifyManageAccessToken('demo_manage_key', $project));
    }

    public function testVerifyManageAccessTokenMissing(): void
    {
        unset($_SERVER['HTTP_AUTHORIZATION']);
        $project = new Project();
        $project->setManageKey('');
        self::assertFalse($this->authService->verifyManageAccessToken(self::MANAGE_KEY_HASH, $project));
    }

    public function testGetTokenFromGlobals(): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer demo_manage_key';
        self::assertEquals('demo_manage_key', $this->authService->getTokenFromGlobals());
    }

    public function testGetTokenFromGlobalsMissing(): void
    {
        unset($_SERVER['HTTP_AUTHORIZATION']);
        self::assertNull($this->authService->getTokenFromGlobals());
    }

    public function testValidateRootToken(): void
    {
        self::assertTrue($this->authService->validateRootToken('root_token'));
        self::assertFalse($this->authService->validateRootToken(''));
    }

    public function testGetProjectReferenceFromGlobals(): void
    {
        $_SERVER['HTTP_PROJECT'] = sprintf('%s/%s', 'antonshell', 'demo');
        $projectReference = $this->authService->getProjectReferenceFromGlobals();
        self::assertEquals('antonshell', $projectReference->getOwner());
        self::assertEquals('demo', $projectReference->getName());
    }

    public function testGetProjectReferenceFromGlobalsNotExists(): void
    {
        unset($_SERVER['HTTP_PROJECT']);
        self::assertNull($this->authService->getProjectReferenceFromGlobals());
    }
}
