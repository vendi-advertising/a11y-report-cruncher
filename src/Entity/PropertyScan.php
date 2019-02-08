<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropertyScanRepository")
 */
class PropertyScan
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Property", inversedBy="propertyScans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $propertyId;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $DateTimeCreated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPropertyId(): ?Property
    {
        return $this->propertyId;
    }

    public function setPropertyId(?Property $propertyId): self
    {
        $this->propertyId = $propertyId;

        return $this;
    }

    public function getDateTimeCreated(): ?\DateTimeInterface
    {
        return $this->DateTimeCreated;
    }

    public function setDateTimeCreated(\DateTimeInterface $DateTimeCreated): self
    {
        $this->DateTimeCreated = $DateTimeCreated;

        return $this;
    }
}
