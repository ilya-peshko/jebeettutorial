<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\ImageTrait;
use App\Entity\Traits\TimestampableEntityTrait;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 * @ORM\Table(name="company")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 * @ApiResource(
 *     normalizationContext={"groups"={"read", "company_imageName", "company"}},
 *     denormalizationContext={"groups"={"write"}}
 * )
 */
class Company implements \Serializable
{
    use ImageTrait;
    use TimestampableEntityTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"company", "job"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     * @Groups({"company"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"company"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"company"})
     */
    private $phone;

    /**
     * @var Job[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Job", mappedBy="company", cascade={"persist", "remove"})
     * @Groups({"company"})
     */
    private $jobs;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User\User", inversedBy="company")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Assert\Type(type="App\Entity\User\User")
     * @Groups({"company", "user"})
     */
    private $user;

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
     * @return int
     */
    public function getCountActiveJobs(): int
    {
        return count($this->jobs->filter(static function (Job $job) {
            return $job->getExpiresAt() > new \DateTime() && $job->isActivated();
        }));
    }

    /**
     * @return int
     */
    public function getCountExpiredJobs(): int
    {
        return count($this->jobs->filter(static function (Job $job) {
            return $job->getExpiresAt() < new \DateTime();
        }));
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return $this
     */
    public function setUser($user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->imageName,
        ]);
    }

    /**
     * @param $serialized
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized): void
    {
        [$this->id,] = unserialize($serialized, ['']);
    }
}
