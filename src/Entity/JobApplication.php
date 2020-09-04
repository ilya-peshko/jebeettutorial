<?php

namespace App\Entity;

use App\Repository\JobApplicationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=JobApplicationRepository::class)
 */
class JobApplication
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"job_application"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Job::class, inversedBy="jobApplications")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"job_application"})
     */
    private $job;

    /**
     * @ORM\ManyToOne(targetEntity=Resume::class, inversedBy="jobApplications")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"job_application"})
     * @Groups({"job_application"})
     */
    private $resume;

    /**
     * @var bool
     * @ORM\Column(name="viewed", type="boolean")
     * @Groups({"job_application"})
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
