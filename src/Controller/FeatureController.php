<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Environment;
use App\Service\AuthService;
use App\Service\EnvironmentService;
use App\Service\FeatureService;
use App\Service\ProjectService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeatureController extends AbstractApiController
{
    private const ERROR_PROJECT_NOT_FOUND = 'Project %s/%s not found';
    private const ERROR_ENVIRONMENT_NOT_FOUND = 'Environment not found: %s';
    private const ERROR_FEATURE_NOT_FOUND = 'Feature not found: %s';

    private const ERROR_INVALID_ACCESS_TOKEN = 'Invalid access token provided';

    public function __construct(
        private AuthService $authService,
        private FeatureService $featureService,
        private ProjectService $projectService,
        private EnvironmentService $environmentService
    ) {
    }

    /**
     * @Route("/feature/{projectOwner}/{projectName}/{featureName}/{env}", name="featureValueForEnv")
     * @Route("/feature/{projectOwner}/{projectName}/{featureName}", name="featureValue")
     */
    public function getFeatureFlag(string $projectOwner, string $projectName, string $featureName, string $env = Environment::ENV_PROD): Response
    {
        $project = $this->projectService->getProjectByNameAndOwner($projectName, $projectOwner);
        if (!$project) {
            return $this->respondJsonError(
                Response::HTTP_FORBIDDEN,
                sprintf(self::ERROR_PROJECT_NOT_FOUND, $projectOwner, $projectName)
            );
        }

        if (!$this->authService->verifyReadAccessToken($project)) {
            return $this->respondJsonError(
                Response::HTTP_FORBIDDEN,
                self::ERROR_INVALID_ACCESS_TOKEN
            );
        }

        $environment = $this->environmentService->getEnvironment($project, $env);
        if (!$environment) {
            return $this->respondJsonError(
                Response::HTTP_NOT_FOUND,
                sprintf(self::ERROR_ENVIRONMENT_NOT_FOUND, $env)
            );
        }

        $feature = $this->featureService->getFeature($project, $featureName);
        if (!$feature) {
            return $this->respondJsonError(
                Response::HTTP_NOT_FOUND,
                sprintf(self::ERROR_FEATURE_NOT_FOUND, $featureName)
            );
        }

        $value = $this->featureService->getFeatureValue($feature, $environment);

        return $this->createApiResponse([
            'status' => 'ok',
            'feature' => $feature->getName(),
            'environment' => $environment->getName(),
            'enabled' => $value->isEnabled(),
        ]);
    }
}
