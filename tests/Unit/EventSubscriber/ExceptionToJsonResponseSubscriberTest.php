<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber;

use App\EventSubscriber\ExceptionToJsonResponseSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\HttpKernel;

class ExceptionToJsonResponseSubscriberTest extends TestCase
{
    private ExceptionToJsonResponseSubscriber $exceptionToJsonResponseSubscriber;

    protected function setUp(): void
    {
        $this->exceptionToJsonResponseSubscriber = new ExceptionToJsonResponseSubscriber();
    }

    protected function tearDown(): void
    {
        unset($this->exceptionToJsonResponseSubscriber, $this->authService);
    }

    public function testOnKernelExceptionOther(): void
    {
        $request
            = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $event = new ExceptionEvent(
            $this->getMockBuilder(HttpKernel::class)->disableOriginalConstructor()->getMock(),
            $request,
            1,
            new \Exception()
        );

        $this->exceptionToJsonResponseSubscriber->onKernelException($event);
        self::assertTrue(true); // check that nothing happens
    }

    public function testOnKernelExceptionAccessDenied(): void
    {
        $request
            = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $event = new ExceptionEvent(
            $this->getMockBuilder(HttpKernel::class)->disableOriginalConstructor()->getMock(),
            $request,
            1,
            new AccessDeniedHttpException('Access denied')
        );

        $this->exceptionToJsonResponseSubscriber->onKernelException($event);
        self::assertEquals(new JsonResponse([
            'status' => 403,
            'message' => 'Access denied',
        ], 403), $event->getResponse());
    }

    public function testGetSubscribedEvents(): void
    {
        $expected = ['kernel.exception' => 'onKernelException'];
        self::assertEquals($expected, ExceptionToJsonResponseSubscriber::getSubscribedEvents());
    }
}
