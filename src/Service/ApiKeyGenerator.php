<?php

declare(strict_types=1);

namespace App\Service;

class ApiKeyGenerator
{
    public function generateApiKey(): string
    {
        /*
         * Generate tokens
         * https://davidwalsh.name/random_bytes
         */
        return bin2hex(random_bytes(64));
    }
}
