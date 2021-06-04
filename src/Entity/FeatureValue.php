<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FeatureValueRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass=FeatureValueRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @Table(name="feature_value",
 *    uniqueConstraints={
 *        @UniqueConstraint(name="feature_value_unique",
 *            columns={"feature_id", "environment_id"})
 *    }
 * )
 */
class FeatureValue
{
    use TimestampedEntityTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @var Feature
     *
     * @ORM\ManyToOne(targetEntity="Feature", inversedBy="values")
     * @ORM\JoinColumn(name="feature_id", referencedColumnName="id")
     */
    private $feature;

    /**
     * @var Environment
     *
     * @ORM\ManyToOne(targetEntity="Environment", inversedBy="featuresValues")
     * @ORM\JoinColumn(name="environment_id", referencedColumnName="id")
     */
    private $environment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getFeature(): ?Feature
    {
        return $this->feature;
    }

    public function setFeature(Feature $feature): self
    {
        $this->feature = $feature;

        return $this;
    }

    public function getEnvironment(): ?Environment
    {
        return $this->environment;
    }

    public function setEnvironment(Environment $environment): self
    {
        $this->environment = $environment;

        return $this;
    }
}
