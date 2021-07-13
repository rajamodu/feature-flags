<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Controller\Api\ManageTokenAuthenticatedController;
use App\Controller\Manage\RootTokenAuthenticatedController;
use App\Repository\ProjectRepository;
use App\Service\AuthService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class TokenSubscriber implements EventSubscriberInterface
{
    private const ERROR_MISSING_TOKEN = 'This action needs a valid token!';
    private const ERROR_INVALID_TOKEN = 'Invalid access token provided';

    public function __construct(
        private AuthService $authService,
        private ProjectRepository $projectRepository,
    ) {
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        $token = $this->authService->getTokenFromGlobals();

        // root token auth
        if ($controller[0] instanceof RootTokenAuthenticatedController) {
            if (!$token) {
                throw new AccessDeniedHttpException(self::ERROR_MISSING_TOKEN);
            }

            if (!$this->authService->validateRootToken($token)) {
                throw new AccessDeniedHttpException(self::ERROR_INVALID_TOKEN);
            }
        }

        // manage token auth
        if ($controller[0] instanceof ManageTokenAuthenticatedController) {
            if (!$token) {
                throw new AccessDeniedHttpException(self::ERROR_MISSING_TOKEN);
            }

            $project = $this->projectRepository->findOneByManageKey($token);
            if (!$project) {
                throw new AccessDeniedHttpException(self::ERROR_INVALID_TOKEN);
            }
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
