<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScannerRepository")
 */
class Scanner implements UserInterface
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
    private $scannerType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTimeCreated;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    public const TYPE_SPIDER = 'spider';

    public const TYPE_REPORT = 'report';

    public static function get_entry_types() : array
    {
        return [
            self::TYPE_SPIDER,
            self::TYPE_REPORT,
        ];
    }

    public function __construct()
    {
        $this->dateTimeCreated = new \DateTime();
        $this->token = $this->generate_random_string();
    }

    protected function generate_random_string() : string
    {
        return hash('sha256', random_bytes(1024));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScannerType(): ?string
    {
        return $this->scannerType;
    }

    public function setScannerType(?string $scannerType): self
    {
        $this->scannerType = $scannerType;

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

    public function getDateTimeCreated(): ?\DateTimeInterface
    {
        return $this->dateTimeCreated;
    }

    public function setDateTimeCreated(\DateTimeInterface $dateTimeCreated): self
    {
        $this->dateTimeCreated = $dateTimeCreated;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getRoles()
    {
        return ['ROLE_API'];
    }

    public function getPassword()
    {
        return null;
        // return '';
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->getName();
    }

    public function eraseCredentials()
    {
        //noop
    }
}
