<?php

declare(strict_types=1);

namespace App\SimpleEntities;

use App\Entity\Scanner;

class UrlForCheckout
{
    private $scanUrlId;

    private $url;

    private $scanType;

    private $checkoutDateTime;

    private function __construct(int $scanUrlId, string $url, string $settingsAsJson, string $scanType)
    {
        $this->scanUrlId = $scanUrlId;
        $this->url = $url;
        $this->scanType = $scanType;
        $this->checkoutDateTime = new \DateTime();
    }

    public function getScanType() : string
    {
        return $this->scanType;
    }

    public function getScanUrlId() : int
    {
        return $this->scanUrlId;
    }

    public function getCheckoutDateTime() : \DateTime
    {
        return $this->checkoutDateTime;
    }

    public static function createForCrawler(int $scanUrlId, string $url) : self
    {
        return new self($scanUrlId, $url, Scanner::TYPE_CRAWLER);
    }

    public static function createForAccessibility(int $scanUrlId, string $url) : self
    {
        return new self($scanUrlId, $url, Scanner::TYPE_A11Y);
    }
}