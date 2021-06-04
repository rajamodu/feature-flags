<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Project
{
    use TimestampedEntityTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $readKey;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $manageKey;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Environment", mappedBy="project")
     */
    private $environments;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Feature", mappedBy="project")
     */
    private $features;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getReadKey(): ?string
    {
        return $this->readKey;
    }

    public function setReadKey(string $read_key): self
    {
        $this->read_key = $read_key;

        return $this;
    }

    public function getManageKey(): ?string
    {
        return $this->manageKey;
    }

    public function setManageKey(string $manage_key): self
    {
        $this->manage_key = $manage_key;

        return $this;
    }

    public function getEnvironments(): Collection
    {
        return $this->environments;
    }

    public function getFeatures(): Collection
    {
        return $this->features;
    }
}
