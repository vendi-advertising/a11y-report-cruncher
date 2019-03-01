<?php

declare(strict_types=1);

namespace App\ReportEntity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

//TODO: Make private properties and add getter/setters
class SingleUrlRollup
{
    public $url;

    public $count_of_pass;

    public $count_of_incomplete_but_serious_or_critical;

    public $count_of_violations_serious;

    public $count_of_violations_critical;

    public $count_of_violations_minor;

    public $count_of_violations_moderate;

    public function get_count_of_violations() : int
    {
        return $this->count_of_violations_serious +
               $this->count_of_violations_critical +
               $this->count_of_violations_minor +
               $this->count_of_violations_moderate
            ;
    }
}