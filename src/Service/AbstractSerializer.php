<?php

declare(strict_types=1);

namespace App\Service;

/**
 * @method serializeItem($entity)
 */
abstract class AbstractSerializer
{
    public function serializeArray(array $data): array
    {
        $results = [];
        foreach ($data as $item) {
            $results[] = $this->serializeItem($item);
        }

        return $results;
    }
}
