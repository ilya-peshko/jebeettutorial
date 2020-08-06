<?php

namespace App\Entity\User;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="user")
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"read", "user"}},
 *     denormalizationContext={"groups"={"write"}},
 *     forceEager = false
 * )
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"user", "company", "resume"})
     * @Assert\NotBlank()
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank()
     * @Groups({"user"})
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=254, unique=true)
     * @Assert\NotBlank()
     * @Groups({"user"})
     */
    private $email;

    /**
     * @var bool
     * @ORM\Column(name="enabled", type="boolean")
     * @Assert\NotBlank()
     * @Groups({"user"})
     */
    private $enabled;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @var array
     * @ORM\Column(type="json")
     * @Assert\NotBlank()
     * @Groups({"user"})
     */
    private $roles;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Company", mappedBy="user")
     * @Assert\NotBlank()
     * @Groups({"user"})
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Resume", mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"resume"})
     */
    private $resumes;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->enabled = false;
        $this->roles = [];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials(): void
    {
        // empty
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // empty
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // we need to make sure to have at least one role
        $roles[] = 'ROLE_USER';

        return array_values(array_unique($roles));
    }

    /**
     * @param $role
     * @return $this
     */
    public function addRole($role): self
    {
        $role = strtoupper($role);

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }
    /**
     * @param $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param $role
     * @return $this
     */
    public function removeRole($role): self
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * @param $username
     * @return $this
     */
    public function setUsername($username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email): ?self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param $boolean
     * @return $this
     */
    public function setEnabled($boolean): self
    {
        $this->enabled = (bool) $boolean;

        return $this;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param \DateTime|null $time
     * @return $this
     */
    public function setLastLogin(\DateTime $time = null): self
    {
        $this->lastLogin = $time;

        return $this;
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = [];

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param $company
     * @return $this
     */
    public function setCompany($company): self
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return string
     */
    public function getMeta(): string
    {
        $userRole = 'anonymous';

        if ($this->hasRole('ROLE_ADMIN')) {
            $userRole = 'Admin';
        } elseif ($this->hasRole('ROLE_EMPLOYER')) {
            $userRole = 'Employer';
        } elseif ($this->hasRole('ROLE_APPLICANT')) {
            $userRole = 'Applicant';
        }

        return $userRole;
    }
}