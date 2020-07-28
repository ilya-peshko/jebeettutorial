<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Company;
use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class JobFixtures
 * @package App\DataFixtures
 */
class JobFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        /** @var Category $category */
        $category = $this->getReference('category-programming');

        /** @var Company $company */
        $company = $this->getReference('company-yarche');

        $jobExpired = new Job();
        $jobExpired->setCategory($category)
            ->setType('full-time')
            ->setCompany($company)
            ->setUrl('http://www.sensiolabs.com/')
            ->setPosition('Web Developer Expired')
            ->setLocation('Paris, France')
            ->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit.')
            ->setHowToApply('Send your resume to lorem.ipsum [at] dolor.sit')
            ->setPublic(true)
            ->setActivated(true)
            ->setEmail('job@example.com')
            ->setExpiresAt(new \DateTime('-10 days'));

        $manager->persist($jobExpired);

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
