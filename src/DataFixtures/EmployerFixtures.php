<?php

namespace App\DataFixtures;

use App\Entity\User\Employer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EmployerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $employer = new Employer();
        $employer->setUsername('Ilya')
            ->setPassword('123')
            ->setEnabled(1)
            ->setEmail('test@mail.ge')
            ->setRoles(['ROLE_EMPLOYER']);

        $manager->persist($employer);
        $manager->flush();

        $this->addReference('employer-one', $employer);
    }
}
