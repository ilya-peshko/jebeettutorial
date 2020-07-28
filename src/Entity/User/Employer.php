<?php

namespace App\Entity\User;

use App\Entity\Company;
use App\Repository\EmployerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmployerRepository::class)
 * @ORM\Table(name="employer")
 */
class Employer extends AbstractUser
{
    /**
     * @var Company[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Company", mappedBy="employer")
     */
    private $companies;

    /**
     * Employer constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->companies = new ArrayCollection();
    }

    /**
     * @return Company[]|ArrayCollection
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * @param Company[]|ArrayCollection $companies
     * @return Employer
     */
    public function setCompanies($companies): self
    {
        $this->companies = $companies;
        return $this;
    }

    /**
     * @param Company $company
     * @return $this
     */
    public function removeCompany(Company $company): self
    {
        $this->companies->removeElement($company);

        return $this;
    }

    /**
     * @param Company $company
     * @return $this
     */
    public function addCompany(Company $company): self
    {
        if (!$this->companies->contains($company)) {
            $this->companies->add($company);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = parent::getRoles();

        $roles[] = 'ROLE_EMPLOYER';

        return array_values(array_unique($roles));
    }
}
