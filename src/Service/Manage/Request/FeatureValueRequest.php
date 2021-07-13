<?php

declare(strict_types=1);

namespace App\Service\Manage\Request;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class FeatureValueRequest
{
    /**
     * @var string
     *
     * @Serializer\SerializedName("environment")
     * @Serializer\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=50)
     *
     * @Assert\Type(type="string")
     */
    private $environment;

    /**
     * @var bool
     *
     * @Serializer\SerializedName("enabled")
     * @Serializer\Type("boolean")
     *
     * @Assert\Type(type="boolean")
     */
    private $enabled;

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }
}
