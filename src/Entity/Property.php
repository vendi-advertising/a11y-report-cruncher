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
     * @ORM\Column(type="datetime")
     */
    private $dateTimeCreated;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PropertyUrl", mappedBy="property", orphanRemoval=true)
     */
    private $propertyUrls;

    public function __construct()
    {
        $this->dateTimeCreated = new \DateTime();
        $this->propertyUrls = new ArrayCollection();
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

    /**
     * @return Collection|PropertyUrl[]
     */
    public function getPropertyUrls(): Collection
    {
        return $this->propertyUrls;
    }

    public function addPropertyUrl(PropertyUrl $propertyUrl): self
    {
        if (!$this->propertyUrls->contains($propertyUrl)) {
            $this->propertyUrls[] = $propertyUrl;
            $propertyUrl->setProperty($this);
        }

        return $this;
    }

    public function removePropertyUrl(PropertyUrl $propertyUrl): self
    {
        if ($this->propertyUrls->contains($propertyUrl)) {
            $this->propertyUrls->removeElement($propertyUrl);
            // set the owning side to null (unless already changed)
            if ($propertyUrl->getProperty() === $this) {
                $propertyUrl->setProperty(null);
            }
        }

        return $this;
    }
}
