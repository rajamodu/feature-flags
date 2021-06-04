<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity of this class have actual timestamps of creation and last update.
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
trait TimestampedEntityTrait
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true, options={"default": "CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * Check docs if planning to do something more "interesting" with this column, for example JOIN.
     *
     * @see http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/annotations-reference.html#column
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true, columnDefinition="DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP")
     */
    private $updatedAt;

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        if (null === $this->updatedAt) {
            return $this->getCreatedAt();
        }

        return $this->updatedAt;
    }

    /**
     * @return static
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return static
     */
    public function setUpdatedAt(?\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));
        if (null === $this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }
}
