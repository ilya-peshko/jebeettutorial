<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\Table(name="categories")
 */
class Category
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @var Job[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Job", mappedBy="category")
     */
    private $jobs;

    /**
     * @var Affiliate[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Affiliate", mappedBy="categories")
     */
    private $affiliates;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->jobs = new ArrayCollection();
        $this->affiliates = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Job[]|ArrayCollection
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * @param Job $job
     *
     * @return self
     */
    public function addJob(Job $job): self
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs->add($job);
        }

        return $this;
    }

    /**
     * @param Job $job
     *
     * @return self
     */
    public function removeJob(Job $job): self
    {
        $this->jobs->removeElement($job);

        return $this;
    }

    /**
     * @return Affiliate[]|ArrayCollection
     */
    public function getAffiliates()
    {
        return $this->affiliates;
    }

    /**
     * @param Affiliate $affiliate
     *
     * @return self
     */
    public function addAffiliate($affiliate): self
    {
        if (!$this->affiliates->contains($affiliate)) {
            $this->affiliates->add($affiliate);
        }

        return $this;
    }

    /**
     * @param Affiliate $affiliate
     *
     * @return self
     */
    public function removeAffiliate($affiliate): self
    {
        $this->affiliates->removeElement($affiliate);

        return $this;
    }
}
