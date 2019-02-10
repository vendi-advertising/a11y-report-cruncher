<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropertyRepository")
 */
class Property
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2048)
     */
    private $RootUrl;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PropertyScan", mappedBy="property", orphanRemoval=true)
     */
    private $propertyScans;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTimeCreated;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $name;

    public function __construct()
    {
        $this->propertyScans = new ArrayCollection();
        $this->dateTimeCreated = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRootUrl(): ?string
    {
        return $this->RootUrl;
    }

    public function setRootUrl(string $RootUrl): self
    {
        $this->RootUrl = $RootUrl;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection|PropertyScan[]
     */
    public function getPropertyScans(): Collection
    {
        return $this->propertyScans;
    }

    public function addPropertyScan(PropertyScan $propertyScan): self
    {
        if (!$this->propertyScans->contains($propertyScan)) {
            $this->propertyScans[] = $propertyScan;
            $propertyScan->setProperty($this);
        }

        return $this;
    }

    public function removePropertyScan(PropertyScan $propertyScan): self
    {
        if ($this->propertyScans->contains($propertyScan)) {
            $this->propertyScans->removeElement($propertyScan);
            // set the owning side to null (unless already changed)
            if ($propertyScan->getProperty() === $this) {
                $propertyScan->setProperty(null);
            }
        }

        return $this;
    }

    public function getDateTimeCreated(): ?\DateTimeInterface
    {
        return $this->dateTimeCreated;
    }

    public function setDateTimeCreated(\DateTimeInterface $dateTimeCreated): self
    {
        $this->dateTimeCreated = $dateTimeCreated;

        return $this;
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
}
