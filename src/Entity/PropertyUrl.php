<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropertyUrlRepository")
 */
class PropertyUrl
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
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Property", inversedBy="propertyUrls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $property;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTimeCreated;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastCrawlDateTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastAcessibilityDateTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastSEODateTime;

    public const STATUS_READY_FOR_CRAWLER = 'ready-for-crawler';

    public const STATUS_READY_FOR_A11Y = 'ready-for-a11y';

    public const STATUS_READY_FOR_SEO = 'ready-for-seo';

    public function __construct()
    {
        $this->dateTimeCreated = new \DateTime();
        $this->status = self::STATUS_READY_FOR_CRAWLER;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): self
    {
        $this->property = $property;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getLastCrawlDateTime(): ?\DateTimeInterface
    {
        return $this->lastCrawlDateTime;
    }

    public function setLastCrawlDateTime(?\DateTimeInterface $lastCrawlDateTime): self
    {
        $this->lastCrawlDateTime = $lastCrawlDateTime;

        return $this;
    }

    public function getLastAcessibilityDateTime(): ?\DateTimeInterface
    {
        return $this->lastAcessibilityDateTime;
    }

    public function setLastAcessibilityDateTime(?\DateTimeInterface $lastAcessibilityDateTime): self
    {
        $this->lastAcessibilityDateTime = $lastAcessibilityDateTime;

        return $this;
    }

    public function getLastSEODateTime(): ?\DateTimeInterface
    {
        return $this->lastSEODateTime;
    }

    public function setLastSEODateTime(?\DateTimeInterface $lastSEODateTime): self
    {
        $this->lastSEODateTime = $lastSEODateTime;

        return $this;
    }
}
