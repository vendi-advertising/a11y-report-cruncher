<?php

declare(strict_types=1);

namespace App\ReportEntity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

//TODO: Make private properties and add getter/setters
class SingleSiteUrlCollection implements \IteratorAggregate
{
    private $urls = [];

    public function add_url(SingleUrlRollup $url){
        $this->urls[] = $url;
    }

    public function getIterator() {
        return new \ArrayIterator($this->urls);
    }
}