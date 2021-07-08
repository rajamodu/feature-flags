<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AbstractApiController;
use App\Repository\EnvironmentRepository;
use App\Repository\ProjectRepository;
use App\Service\Api\Request\EnvironmentRequest;
use App\Service\AuthService;
use App\Service\EnvironmentService;
use App\Service\Manage\Request\ProjectRequest;
use App\Service\Api\Serializer\EnvironmentSerializer;
use App\Service\Manage\Serializer\ProjectSerializer;
use App\Service\ProjectService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EnvironmentController extends AbstractApiController
{
    public function __construct(
        private AuthService $authService,
        private EnvironmentService $environmentService,
        private EnvironmentRepository $environmentRepository,
        private ProjectRepository $projectRepository,
        private EnvironmentSerializer $environmentSerializer,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * @Route("/api/environments", name="getProjectEnvironments")
     */
    public function getProjectEnvironments(Request $request): JsonResponse
    {
        $project = $this->authService->getProjectByManageKey();
        if (!$project) {
            return $this->respondNotFound();
        }

        $environments = $this->environmentRepository->findAllByProject($project);
        $data = $this->environmentSerializer->serializeArray($environments);

        return $this->createApiResponse($data);
    }

    /**
     * @Route("/api/environment/{name}", name="getEnvironment", methods={"GET"})
     */
    public function getByName(string $name): JsonResponse
    {
        $project = $this->authService->getProjectByManageKey();
        if (!$project) {
            return $this->respondNotFound();
        }

        $environment = $this->environmentService->getEnvironment($project, $name);
        if (!$environment) {
            return $this->respondNotFound();
        }

        $data = $this->environmentSerializer->serializeItem($environment);

        return $this->createApiResponse($data);
    }

    /**
     * @Route("/api/environment", name="createEnvironment", methods={"POST"})
     * @ParamConverter(
     *      "environmentRequest",
     *      converter="fos_rest.request_body",
     *      class="App\Service\Api\Request\EnvironmentRequest"
     * )
     *
     * @throws \Exception
     */
    public function create(EnvironmentRequest $environmentRequest): JsonResponse
    {
        $project = $this->authService->getProjectByManageKey();
        if (!$project) {
            return $this->respondNotFound();
        }

        $validationErrors = $this->validator->validate($environmentRequest);
        if ($validationErrors->count() > 0) {
            $errors = $this->getErrorMessages($validationErrors);

            return $this->respondValidationError($errors);
        }

        $environment = $this->environmentService->createEnvironment($project, $environmentRequest);
        $data = $this->environmentSerializer->serializeItem($environment);

        return $this->createApiResponse($data);
    }

    /**
     * @Route("/api/environment/{name}", name="updateEnvironment", methods={"POST"})
     * @ParamConverter(
     *      "environmentRequest",
     *      converter="fos_rest.request_body",
     *      class="App\Service\Api\Request\EnvironmentRequest"
     * )
     */
    public function update(string $name, EnvironmentRequest $environmentRequest): JsonResponse
    {
        $project = $this->authService->getProjectByManageKey();
        if (!$project) {
            return $this->respondNotFound();
        }

        $environment = $this->environmentService->getEnvironment($project, $name);
        if (!$environment) {
            return $this->respondNotFound();
        }

        $validationErrors = $this->validator->validate($environmentRequest);
        if ($validationErrors->count() > 0) {
            $errors = $this->getErrorMessages($validationErrors);

            return $this->respondValidationError($errors);
        }

        $environment = $this->environmentService->updateEnvironment($environment, $environmentRequest);
        $data = $this->environmentSerializer->serializeItem($environment);

        return $this->createApiResponse($data);
    }

    /**
     * @Route("/api/environment/{name}", name="deleteEnvironment", methods={"DELETE"})
     */
    public function delete(string $name): JsonResponse
    {
        $project = $this->authService->getProjectByManageKey();
        if (!$project) {
            return $this->respondNotFound();
        }

        $environment = $this->environmentService->getEnvironment($project, $name);
        if (!$environment) {
            return $this->respondNotFound();
        }

        $this->environmentRepository->remove($environment);

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}
