<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractApiController extends AbstractController
{
    use ApiControllerResponseTrait;
    use ApiControllerValidationTrait;
    private const ERROR_NOT_FOUND = 'Not found';
    private const ERROR_VALIDATION_FAILED = 'Validation failed';
}
