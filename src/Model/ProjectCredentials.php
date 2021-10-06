<?php

declare(strict_types=1);

namespace App\Model;

class ProjectCredentials
{
    public function __construct(
        private string $readKey,
        private string $manageKey
    ) {
    }

    public function getReadKey(): string
    {
        return $this->readKey;
    }

    public function getManageKey(): string
    {
        return $this->manageKey;
    }
}
