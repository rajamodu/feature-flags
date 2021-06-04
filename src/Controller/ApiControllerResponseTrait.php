<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiControllerResponseTrait
{
    protected function createApiResponse(array $data): JsonResponse
    {
        $response = new JsonResponse($data);
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        return $response;
    }

    protected function respondNotFound(): JsonResponse
    {
        return $this->respondJsonError(Response::HTTP_NOT_FOUND, 'Not found');
    }

    protected function respondJsonError(int $status, string $message): JsonResponse
    {
        return new JsonResponse(['status' => $status, 'message' => $message], Response::HTTP_NOT_FOUND);
    }
}
