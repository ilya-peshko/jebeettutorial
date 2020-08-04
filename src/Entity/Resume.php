<?php

namespace App\Entity;

use App\Entity\User\User;
use App\Repository\ResumeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResumeRepository::class)
 * @ORM\Table(name="resumes")
 */
class Resume
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $cityOfResidence;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     */
    private $gender;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $dateOfBirthday;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $aboutMe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User", inversedBy="resumes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $viewsCount;

    /**
     * @ORM\OneToMany(targetEntity=JobApplication::class, mappedBy="resume", cascade={"persist", "remove"})
     */
    private $jobApplications;

    /**
     * Resume constructor.
     */
    public function __construct()
    {
        $this->jobApplications = new ArrayCollection();
        $this->viewsCount = 0;
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
     * @return Resume
     */
    public function setName(string $name): Resume
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return Resume
     */
    public function setSurname(string $surname): Resume
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCityOfResidence(): ?string
    {
        return $this->cityOfResidence;
    }

    /**
     * @param string $cityOfResidence
     * @return Resume
     */
    public function setCityOfResidence(string $cityOfResidence): Resume
    {
        $this->cityOfResidence = $cityOfResidence;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return Resume
     */
    public function setGender(string $gender): Resume
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateOfBirthday(): ?\DateTime
    {
        return $this->dateOfBirthday;
    }

    /**
     * @param \DateTime $dateOfBirthday
     * @return Resume
     */
    public function setDateOfBirthday(\DateTime $dateOfBirthday): Resume
    {
        $this->dateOfBirthday = $dateOfBirthday;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Resume
     */
    public function setTitle(string $title): Resume
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param int $viewsCount
     * @return Resume
     */
    public function setViewsCount(int $viewsCount): self
    {
        $this->viewsCount = $viewsCount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getViewsCount()
    {
        return $this->viewsCount;
    }

    /**
     * @return $this
     */
    public function increaseViewsCount(): self
    {
        $this->viewsCount++;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param $user
     * @return $this
     */
    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAboutMe(): ?string
    {
        return $this->aboutMe;
    }

    /**
     * @param string $aboutMe
     * @return Resume
     */
    public function setAboutMe(string $aboutMe): Resume
    {
        $this->aboutMe = $aboutMe;

        return $this;
    }

    /**
     * @return Collection|JobApplication[]
     */
    public function getJobApplications(): Collection
    {
        return $this->jobApplications;
    }

    /**
     * @param JobApplication $jobApplication
     * @return $this
     */
    public function addJobApplication(JobApplication $jobApplication): self
    {
        if (!$this->jobApplications->contains($jobApplication)) {
            $this->jobApplications[] = $jobApplication;
            $jobApplication->setResume($this);
        }

        return $this;
    }

    /**
     * @param JobApplication $jobApplication
     * @return $this
     */
    public function removeJobApplication(JobApplication $jobApplication): self
    {
        if ($this->jobApplications->contains($jobApplication)) {
            $this->jobApplications->removeElement($jobApplication);
            // set the owning side to null (unless already changed)
            if ($jobApplication->getResume() === $this) {
                $jobApplication->setResume(null);
            }
        }

        return $this;
    }

}
