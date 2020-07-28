<?php

namespace App\Entity\User;

use App\Repository\ApplicantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApplicantRepository::class)
 * @ORM\Table(name="applicant")
 */
class Applicant extends AbstractUser
{
    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = parent::getRoles();

        $roles[] = 'ROLE_APPLICANT';

        return array_values(array_unique($roles));
    }

}
