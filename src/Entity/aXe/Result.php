<?php declare(strict_types=1);

namespace App\Entity\aXe;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\aXe\ResultRepository")
 * @ORM\Table(name="axe_result")
 */
class Result
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $resultType;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $html;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $target = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResultType(): ?ResultType
    {
        return $this->resultType;
    }

    public function setResultType(?ResultType $resultType): self
    {
        $this->resultType = $resultType;

        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(?string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function getTarget(): ?array
    {
        return $this->target;
    }

    public function setTarget(?array $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }
}
