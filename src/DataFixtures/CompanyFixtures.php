<?php

namespace App\DataFixtures;
use App\Entity\Company;
use App\Entity\User\User;
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
        /** @var User $user_one */
        $user_one = $this->getReference('user-one');

        $company = new Company();
        $company->setName("Company - 1")
            ->setAddress("Pirog - 1")
            ->setUser($user_one);

        $manager->persist($company);

        $this->addReference("company-1", $company);

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
