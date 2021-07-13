<?php

declare(strict_types=1);

namespace App\Service\Manage\Request;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class EnvironmentRequest
{
    /**
     * @var string
     *
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=50)
     *
     * @Assert\Type(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @Serializer\SerializedName("description")
     * @Serializer\Type("string")
     * @Assert\Type(type="string")
     */
    private $description;

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
