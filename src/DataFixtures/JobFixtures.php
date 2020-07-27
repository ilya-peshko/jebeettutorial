<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class JobFixtures
 * @package App\DataFixtures
 */
class JobFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $jobExpired = new Job();

        /** @var Category $category */
        $category = $this->getReference('category-programming');

        $jobExpired = new Job();
        $jobExpired->setCategory($category)
            ->setType('full-time')
            ->setCompany('Sensio Labs')
            ->setUrl('http://www.sensiolabs.com/')
            ->setPosition('Web Developer Expired')
            ->setLocation('Paris, France')
            ->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit.')
            ->setHowToApply('Send your resume to lorem.ipsum [at] dolor.sit')
            ->setPublic(true)
            ->setActivated(true)
            ->setToken('job_expired')
            ->setEmail('job@example.com')
            ->setExpiresAt(new \DateTime('-10 days'));

        $jobSensioLabs = new Job();

        $jobSensioLabs->setCategory($category)
            ->setType('full-time')
            ->setCompany('Sensio Labs')
            ->setUrl('http://www.sensiolabs.com/')
            ->setPosition('Web Developer')
            ->setLocation('Paris, France')
            ->setDescription('You\'ve already developed websites with symfony and you want to work with Open-Source technologies. You have a minimum of 3 years experience in web development with PHP or Java and you wish to participate to development of Web 2.0 sites using the best frameworks available.')
            ->setHowToApply('Send your resume to fabien.potencier [at] sensio.com')
            ->setPublic(true)
            ->setActivated(true)
            ->setToken('job_sensio_labs')
            ->setEmail('job@example.com')
            ->setExpiresAt(new \DateTime('+30 days'));

        $jobExtremeSensio = new Job();

        $jobExtremeSensio->setCategory($category)
            ->setType('part-time')
            ->setCompany('Extreme Sensio')
            ->setUrl('http://www.extreme-sensio.com/')
            ->setPosition('Web Designer')
            ->setLocation('Paris, France')
            ->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in.')
            ->setHowToApply('Send your resume to fabien.potencier [at] sensio.com')
            ->setPublic(true)
            ->setActivated(true)
            ->setToken('job_extreme_sensio')
            ->setEmail('job@example.com')
            ->setExpiresAt(new \DateTime('+30 days'));

        for ($i = 100; $i <= 130; $i++) {
            $job = new Job();
            $job->setCategory($category)
            ->setType('full-time')
            ->setCompany('Company ' . $i)
            ->setPosition('Web Developer')
            ->setLocation('Paris, France')
            ->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit.')
            ->setHowToApply('Send your resume to lorem.ipsum [at] dolor.sit')
            ->setPublic(true)
            ->setActivated(true)
            ->setToken('job_' . $i)
            ->setEmail('job@example.com');

            $manager->persist($job);
        }
        $manager->persist($jobExpired);
        $manager->persist($jobSensioLabs);
        $manager->persist($jobExtremeSensio);

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
