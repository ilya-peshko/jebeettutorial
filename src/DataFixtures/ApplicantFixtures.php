<?php

namespace App\DataFixtures;

use App\Entity\User\Applicant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ApplicantFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $applicant = new Applicant();
        $applicant->setUsername('Andrey')
            ->setPassword('321')
            ->setEnabled(true)
            ->setEmail('tset@liam.eg')
            ->setRoles(['ROLE_APPLICANT']);

        $manager->persist($applicant);
        $manager->flush();
    }
}
