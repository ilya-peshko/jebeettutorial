<?php

namespace App\Entity;

use App\Repository\JobApplicationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JobApplicationRepository::class)
 */
class JobApplication
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Job::class, inversedBy="jobApplications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $job;

    /**
     * @ORM\ManyToOne(targetEntity=Resume::class, inversedBy="jobApplications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $resume;

    /**
     * @var bool
     * @ORM\Column(name="viewed", type="boolean")
     */
    private $viewed;

    /**
     * JobApplication constructor.
     */
    public function __construct()
    {
        $this->viewed = false;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Job|null
     */
    public function getJob(): ?Job
    {
        return $this->job;
    }

    /**
     * @param Job|null $job
     * @return $this
     */
    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }

    /**
     * @return Resume|null
     */
    public function getResume(): ?Resume
    {
        return $this->resume;
    }

    /**
     * @param Resume|null $resume
     * @return $this
     */
    public function setResume(?Resume $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * @param $boolean
     * @return $this
     */
    public function setViewed($boolean): self
    {
        $this->viewed = (bool)$boolean;

        return $this;
    }

    /**
     * @return bool
     */
    public function isViewed(): bool
    {
        return $this->viewed;
    }
}
