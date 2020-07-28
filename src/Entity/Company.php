<?php

namespace App\Entity;

use App\Entity\User\Employer;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 * @ORM\Table(name="company")
 */
class Company
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @var Employer
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\Employer", inversedBy="companies")
     * @ORM\JoinColumn(name="employer_id", referencedColumnName="id", nullable=false)
     */
    private $employer;

    /**
     * @var Job[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Job", mappedBy="company")
     */
    private $jobs;

    /**
     * Company constructor.
     */
    public function __construct()
    {
        $this->jobs = new ArrayCollection();
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
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return $this
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Employer
     */
    public function getEmployer(): Employer
    {
        return $this->employer;
    }

    /**
     * @param Employer $employer
     * @return $this
     */
    public function setEmployer(Employer $employer): self
    {
        $this->employer = $employer;

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
     * @param Job[]|ArrayCollection $jobs
     * @return Company
     */
    public function setJobs($jobs): self
    {
        $this->jobs = $jobs;
        return $this;
    }

    /**
     * @param Job $job
     * @return $this
     */
    public function addJobs(Job $job): self
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs->add($job);
        }

        return $this;
    }

    /**
     * @param Job $job
     * @return $this
     */
    public function removeCompany(Job $job): self
    {
        $this->jobs->removeElement($job);

        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->name;
    }
}
