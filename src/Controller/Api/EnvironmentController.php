<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AbstractApiController;
use App\Repository\EnvironmentRepository;
use App\Service\Api\Request\EnvironmentRequest;
use App\Service\Api\Serializer\EnvironmentSerializer;
use App\Service\AuthService;
use App\Service\EnvironmentService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EnvironmentController extends AbstractApiController implements ManageTokenAuthenticatedController
{
    public function __construct(
        private AuthService $authService,
        private EnvironmentService $environmentService,
        private EnvironmentRepository $environmentRepository,
        private EnvironmentSerializer $environmentSerializer,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * @Route("/api/environments", name="getProjectEnvironments")
     */
    public function getProjectEnvironments(): JsonResponse
    {
        $project = $this->authService->getProjectByManageKey();
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
        $validationErrors = $this->validator->validate($environmentRequest);
        if ($validationErrors->count() > 0) {
            $errors = $this->getErrorMessages($validationErrors);

            return $this->respondValidationError($errors);
        }

        $project = $this->authService->getProjectByManageKey();
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
        $environment = $this->environmentService->getEnvironment($project, $name);
        if (!$environment) {
            return $this->respondNotFound();
        }

        $this->environmentService->delete($environment);

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}
