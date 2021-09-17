<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

trait ApiControllerValidationTrait
{
    protected function respondValidationError(array $messages): JsonResponse
    {
        return new JsonResponse([
            'status' => Response::HTTP_BAD_REQUEST,
            'message' => 'Validation failed',
            'validation_errors' => $messages,
        ]);
    }

    protected function getErrorMessages(ConstraintViolationListInterface $violations): array
    {
        $messages = [];
        foreach ($violations as $violation) {
            $property = $violation->getPropertyPath();
            $property = trim($property, '[');
            $property = rtrim($property, ']');
            $messages[$property] = $violation->getMessage();
        }

        return $messages;
    }
}
