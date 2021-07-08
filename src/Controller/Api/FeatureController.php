<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AbstractApiController;
use App\Repository\FeatureRepository;
use App\Service\Api\Request\FeatureRequest;
use App\Service\Api\Serializer\FeatureSerializer;
use App\Service\AuthService;
use App\Service\FeatureService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FeatureController extends AbstractApiController
{
    public function __construct(
        private AuthService $authService,
        private FeatureService $featureService,
        private FeatureRepository $featureRepository,
        private FeatureSerializer $featureSerializer,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * @Route("/api/features", name="getProjectFeatures")
     */
    public function getProjectFeatures(Request $request): JsonResponse
    {
        $project = $this->authService->getProjectByManageKey();
        if (!$project) {
            return $this->respondNotFound();
        }

        $environments = $this->featureRepository->findAllByProject($project);
        $data = $this->featureSerializer->serializeArray($environments);

        return $this->createApiResponse($data);
    }

    /**
     * @Route("/api/feature/{name}", name="getFeature", methods={"GET"})
     */
    public function getByName(string $name): JsonResponse
    {
        $project = $this->authService->getProjectByManageKey();
        if (!$project) {
            return $this->respondNotFound();
        }

        $feature = $this->featureService->getFeature($project, $name);
        if (!$feature) {
            return $this->respondNotFound();
        }

        $data = $this->featureSerializer->serializeItem($feature);

        return $this->createApiResponse($data);
    }

    /**
     * @Route("/api/feature", name="createFeature", methods={"POST"})
     * @ParamConverter(
     *      "featureRequest",
     *      converter="fos_rest.request_body",
     *      class="App\Service\Api\Request\FeatureRequest"
     * )
     *
     * @throws \Exception
     */
    public function create(FeatureRequest $featureRequest): JsonResponse
    {
        $project = $this->authService->getProjectByManageKey();
        if (!$project) {
            return $this->respondNotFound();
        }

        $validationErrors = $this->validator->validate($featureRequest);
        if ($validationErrors->count() > 0) {
            $errors = $this->getErrorMessages($validationErrors);

            return $this->respondValidationError($errors);
        }

        $feature = $this->featureService->createFeature($project, $featureRequest);
        $data = $this->featureSerializer->serializeItem($feature);

        return $this->createApiResponse($data);
    }

    /**
     * @Route("/api/feature/{name}", name="updateFeature", methods={"POST"})
     * @ParamConverter(
     *      "featureRequest",
     *      converter="fos_rest.request_body",
     *      class="App\Service\Api\Request\FeatureRequest"
     * )
     */
    public function update(string $name, FeatureRequest $featureRequest): JsonResponse
    {
        $project = $this->authService->getProjectByManageKey();
        if (!$project) {
            return $this->respondNotFound();
        }

        $feature = $this->featureService->getFeature($project, $name);
        if (!$feature) {
            return $this->respondNotFound();
        }

        $validationErrors = $this->validator->validate($featureRequest);
        if ($validationErrors->count() > 0) {
            $errors = $this->getErrorMessages($validationErrors);

            return $this->respondValidationError($errors);
        }

        $environment = $this->featureService->updateFeature($feature, $featureRequest);
        $data = $this->featureSerializer->serializeItem($environment);

        return $this->createApiResponse($data);
    }

    /**
     * @Route("/api/feature/{name}", name="deleteFeature", methods={"DELETE"})
     */
    public function delete(string $name): JsonResponse
    {
        $project = $this->authService->getProjectByManageKey();
        if (!$project) {
            return $this->respondNotFound();
        }

        $feature = $this->featureService->getFeature($project, $name);
        if (!$feature) {
            return $this->respondNotFound();
        }

        $this->featureService->delete($feature);

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}
