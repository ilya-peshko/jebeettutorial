<?php

namespace App\DataFixtures;

use App\Entity\Resume;
use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ResumeFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var User $applicant */
        $applicant = $this->getReference('user-applicant');

        $resume_one = new Resume();
        $resume_one->setUser($applicant)
            ->setTitle('My first resume')
            ->setAboutMe('Bum shakalaka');

            $manager->persist($resume_one);

        $resume_two = new Resume();
        $resume_two->setUser($applicant)
            ->setTitle('My second resume')
            ->setAboutMe('Hop hey lala ley');

            $manager->persist($resume_two);

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
