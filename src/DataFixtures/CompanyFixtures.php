<?php

namespace App\DataFixtures;
use App\Entity\Company;
use App\Entity\User\Employer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        /** @var Employer $employer_one */
        $employer_one = $this->getReference('employer-one');

        $company = new Company();
        $company->setName('Yarche')
            ->setAddress('Pirogova')
            ->setEmployer($employer_one);

        $manager->persist($company);
        $manager->flush();

        $this->addReference('company-yarche', $company);
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            EmployerFixtures::class,
        ];
    }
}
