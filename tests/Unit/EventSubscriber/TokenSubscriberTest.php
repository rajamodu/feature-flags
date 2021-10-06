<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber;

use App\Controller\MainController;
use App\Controller\Manage\FeatureController;
use App\Controller\Root\ProjectController;
use App\Entity\Project;
use App\EventSubscriber\TokenSubscriber;
use App\Model\ProjectReference;
use App\Repository\FeatureRepository;
use App\Repository\ProjectRepository;
use App\Service\AuthService;
use App\Service\FeatureService;
use App\Service\Manage\Serializer\FeatureSerializer;
use App\Service\ProjectService;
use App\Service\Root\Serializer\ProjectSerializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TokenSubscriberTest extends TestCase
{
    private const ROOT_TOKEN = 'root_token';
    private const MANAGE_TOKEN = 'manage_token';

    private AuthService $authService;
    private ProjectRepository $projectRepository;
    private TokenSubscriber $tokenSubscriber;

    protected function setUp(): void
    {
        $this->authService
            = $this->getMockBuilder(AuthService::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->projectRepository
            = $this->getMockBuilder(ProjectRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->tokenSubscriber = new TokenSubscriber($this->authService, $this->projectRepository);
    }

    protected function tearDown(): void
    {
        unset($this->tokenSubscriber, $this->authService, $this->projectRepository);
    }

    public function testOnKernelControllerMain(): void
    {
        $controller = new MainController();
        $event = new ControllerEvent(
            $this->getMockBuilder(HttpKernel::class)->disableOriginalConstructor()->getMock(),
            [$controller, 'index'],
            $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock(),
            1
        );

        $this->tokenSubscriber->onKernelController($event);
        self::assertTrue(true); // check that nothing happens
    }

    public function testOnKernelControllerRootToken(): void
    {
        $controller = $this->getProjectController();

        $this->authService
            ->expects(self::once())
            ->method('getTokenFromGlobals')
            ->willReturn(self::ROOT_TOKEN)
        ;

        $this->authService
            ->expects(self::once())
            ->method('validateRootToken')
            ->with(self::ROOT_TOKEN)
            ->willReturn(true)
        ;

        $event = new ControllerEvent(
            $this->getMockBuilder(HttpKernel::class)->disableOriginalConstructor()->getMock(),
            [$controller, 'getAll'],
            $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock(),
            1
        );

        $this->tokenSubscriber->onKernelController($event);
    }

    public function testOnKernelControllerRootTokenMissing(): void
    {
        $controller = $this->getProjectController();

        $this->authService
            ->expects(self::once())
            ->method('getTokenFromGlobals')
            ->willReturn('')
        ;

        $event = new ControllerEvent(
            $this->getMockBuilder(HttpKernel::class)->disableOriginalConstructor()->getMock(),
            [$controller, 'getAll'],
            $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock(),
            1
        );

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('This action needs a valid token!');
        $this->tokenSubscriber->onKernelController($event);
    }

    public function testOnKernelControllerRootTokenInvalid(): void
    {
        $controller = $this->getProjectController();

        $this->authService
            ->expects(self::once())
            ->method('getTokenFromGlobals')
            ->willReturn(self::ROOT_TOKEN)
        ;

        $this->authService
            ->expects(self::once())
            ->method('validateRootToken')
            ->with(self::ROOT_TOKEN)
            ->willReturn(false)
        ;

        $event = new ControllerEvent(
            $this->getMockBuilder(HttpKernel::class)->disableOriginalConstructor()->getMock(),
            [$controller, 'getAll'],
            $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock(),
            1
        );

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Invalid access token provided');
        $this->tokenSubscriber->onKernelController($event);
    }

    public function testOnKernelControllerManageToken(): void
    {
        $controller = $this->getFeatureController();

        $project = new Project();
        $projectReference = new ProjectReference('antonshell','demo');

        $this->authService
            ->expects(self::once())
            ->method('getTokenFromGlobals')
            ->willReturn(self::MANAGE_TOKEN)
        ;


        $this->authService
            ->expects(self::once())
            ->method('getProjectReferenceFromGlobals')
            ->willReturn($projectReference)
        ;

        $this->authService
            ->expects(self::once())
            ->method('verifyManageAccessToken')
            ->with(self::MANAGE_TOKEN, $project)
            ->willReturn(true)
        ;

        $this->projectRepository
            ->expects(self::once())
            ->method('findOneByOwnerAndName')
            ->with('antonshell','demo')
            ->willReturn($project)
        ;

        $event = new ControllerEvent(
            $this->getMockBuilder(HttpKernel::class)->disableOriginalConstructor()->getMock(),
            [$controller, 'getProjectFeatures'],
            $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock(),
            1
        );

        $this->tokenSubscriber->onKernelController($event);
    }

    public function testOnKernelControllerManageTokenMissing(): void
    {
        $controller = $this->getFeatureController();

        $this->authService
            ->expects(self::once())
            ->method('getTokenFromGlobals')
            ->willReturn('')
        ;

        $event = new ControllerEvent(
            $this->getMockBuilder(HttpKernel::class)->disableOriginalConstructor()->getMock(),
            [$controller, 'getProjectFeatures'],
            $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock(),
            1
        );

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('This action needs a valid token!');
        $this->tokenSubscriber->onKernelController($event);
    }

    public function testOnKernelControllerProjectReferenceMissing(): void
    {
        $controller = $this->getFeatureController();

        $this->authService
            ->expects(self::once())
            ->method('getTokenFromGlobals')
            ->willReturn(self::MANAGE_TOKEN)
        ;

        $this->authService
            ->expects(self::once())
            ->method('getProjectReferenceFromGlobals')
            ->willReturn(null)
        ;

        $event = new ControllerEvent(
            $this->getMockBuilder(HttpKernel::class)->disableOriginalConstructor()->getMock(),
            [$controller, 'getProjectFeatures'],
            $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock(),
            1
        );

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('This action needs a valid project reference!');
        $this->tokenSubscriber->onKernelController($event);
    }

    public function testOnKernelControllerManageTokenInvalid(): void
    {
        $controller = $this->getFeatureController();

        $project = new Project();
        $this->authService
            ->expects(self::once())
            ->method('getTokenFromGlobals')
            ->willReturn(self::MANAGE_TOKEN)
        ;

        $projectReference = new ProjectReference('antonshell','demo');
        $this->authService
            ->expects(self::once())
            ->method('getProjectReferenceFromGlobals')
            ->willReturn($projectReference)
        ;

        $this->authService
            ->expects(self::once())
            ->method('verifyManageAccessToken')
            ->with(self::MANAGE_TOKEN, $project)
            ->willReturn(false)
        ;

        $this->projectRepository
            ->expects(self::once())
            ->method('findOneByOwnerAndName')
            ->with('antonshell','demo')
            ->willReturn($project)
        ;

        $event = new ControllerEvent(
            $this->getMockBuilder(HttpKernel::class)->disableOriginalConstructor()->getMock(),
            [$controller, 'getProjectFeatures'],
            $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock(),
            1
        );

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Invalid credentials provided');
        $this->tokenSubscriber->onKernelController($event);
    }

    public function testOnKernelControllerWrongController(): void
    {
        $event = new ControllerEvent(
            $this->getMockBuilder(HttpKernel::class)->disableOriginalConstructor()->getMock(),
            function () {},
            $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock(),
            1
        );

        $this->tokenSubscriber->onKernelController($event);
        self::assertTrue(true); // check that nothing happens
    }

    public function testGetSubscribedEvents(): void
    {
        $expected = ['kernel.controller' => 'onKernelController'];
        self::assertEquals($expected, TokenSubscriber::getSubscribedEvents());
    }

    private function getProjectController(): ProjectController
    {
        $projectService
            = $this->getMockBuilder(ProjectService::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $projectRepository
            = $this->getMockBuilder(ProjectRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $projectSerializer
            = $this->getMockBuilder(ProjectSerializer::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $validator
            = $this->getMockBuilder(ValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return new ProjectController($projectService, $projectRepository, $projectSerializer, $validator);
    }

    private function getFeatureController(): FeatureController
    {
        $authService
            = $this->getMockBuilder(AuthService::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $featureService
            = $this->getMockBuilder(FeatureService::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $featureRepository
            = $this->getMockBuilder(FeatureRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $featureSerializer
            = $this->getMockBuilder(FeatureSerializer::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $validator
            = $this->getMockBuilder(ValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return new FeatureController($authService, $featureService, $featureRepository, $featureSerializer, $validator);
    }
}
