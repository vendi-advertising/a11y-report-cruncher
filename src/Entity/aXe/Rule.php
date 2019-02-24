<?php

namespace App\Entity\aXe;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\aXe\RuleRepository")
 * @ORM\Table(name="axe_rule")
 */
class Rule
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $impact;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\aXe\Tag", inversedBy="rules")
     * @ORM\JoinTable(name="axe_rules_tags")
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $help;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\aXe\Check", inversedBy="rules")
     * @ORM\JoinTable(name="axe_rules_checks")
     */
    private $checks;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->checks = new ArrayCollection();
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

    public function getImpact(): ?string
    {
        return $this->impact;
    }

    public function setImpact(?string $impact): self
    {
        $this->impact = $impact;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function addTags(Iterable $tags) : self
    {
        foreach($tags as $tag){
            $this->addTag($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getHelp(): ?string
    {
        return $this->help;
    }

    public function setHelp(?string $help): self
    {
        $this->help = $help;

        return $this;
    }

    public function get_check_names_joined() : string
    {
        $checks = $this->getChecks();

        $names = array_map(
            function($check)
            {
                return $check->getName();
            },
            $checks->toArray()
        );

        asort($names);

        return implode(',', $names);
    }

    // public function get_cache_key() : string
    // {
    //     $raw = $this->getName();

    //     $funcs = ['getChecksAll', 'getChecksNone', 'getChecksAny'];
    //     foreach($funcs as $func){
    //         foreach($this->$func() as $check){
    //             $raw .= $check->getName();
    //         }
    //     }

    //     return \hash('sha256', $raw);

    // }

    /**
     * @return Collection|Check[]
     */
    public function getChecks(): Collection
    {
        return $this->checks;
    }

    public function addCheck(Check $check): self
    {
        if (!$this->checks->contains($check)) {
            $this->checks[] = $check;
        }

        return $this;
    }

    public function addChecks(Iterable $checks) : self
    {
        foreach($checks as $check){
            $this->addCheck($check);
        }

        return $this;
    }

    public function removeCheck(Check $check): self
    {
        if ($this->checks->contains($check)) {
            $this->checks->removeElement($check);
        }

        return $this;
    }
}
