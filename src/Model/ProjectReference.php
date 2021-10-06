<?php

declare(strict_types=1);

namespace App\Model;

class ProjectReference
{
    public function __construct(
        private string $owner,
        private string $name
    ) {
    }

    public function getOwner(): string
    {
        return $this->owner;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
