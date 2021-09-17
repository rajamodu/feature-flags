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
    protected const ERROR_VALUE_NOT_SET_FOR_ENV = 'Feature(%s) value is not set for environment(%s)';
    protected const ERROR_REMOVE_PROD_ENV = 'Cant remove production environment';
}
