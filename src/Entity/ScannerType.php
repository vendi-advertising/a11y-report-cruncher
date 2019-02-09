<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScannerTypeRepository")
 */
class ScannerType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Scanner", mappedBy="scannerType")
     */
    private $scanners;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTimeCreated;

    public function __construct()
    {
        $this->scanners = new ArrayCollection();
        $this->dateTimeCreated = new \DateTime();
    }

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

    /**
     * @return Collection|Scanner[]
     */
    public function getScanners(): Collection
    {
        return $this->scanners;
    }

    public function addScanner(Scanner $scanner): self
    {
        if (!$this->scanners->contains($scanner)) {
            $this->scanners[] = $scanner;
            $scanner->setScannerType($this);
        }

        return $this;
    }

    public function removeScanner(Scanner $scanner): self
    {
        if ($this->scanners->contains($scanner)) {
            $this->scanners->removeElement($scanner);
            // set the owning side to null (unless already changed)
            if ($scanner->getScannerType() === $this) {
                $scanner->setScannerType(null);
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
}
