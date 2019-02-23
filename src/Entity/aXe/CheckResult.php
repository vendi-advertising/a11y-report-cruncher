<?php

namespace App\Entity\aXe;

use App\Entity\aXe\Check;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\aXe\CheckResultRepository")
 * @ORM\Table(name="axe_check_result")
 */
class CheckResult
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\aXe\Check")
     * @ORM\JoinColumn(nullable=false)
     */
    private $basedOnCheck;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $data = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $relatedNodes = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBasedOnCheck(): ?Check
    {
        return $this->basedOnCheck;
    }

    public function setBasedOnCheck(?Check $basedOnCheck): self
    {
        $this->basedOnCheck = $basedOnCheck;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getRelatedNodes(): ?array
    {
        return $this->relatedNodes;
    }

    public function setRelatedNodes(?array $relatedNodes): self
    {
        $this->relatedNodes = $relatedNodes;

        return $this;
    }
}
