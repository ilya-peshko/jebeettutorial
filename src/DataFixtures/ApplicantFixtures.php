<?php

namespace App\DataFixtures;

use App\Entity\User\Applicant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApplicantFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    /**
     * ApplicantFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $applicant = new Applicant();
        $applicant->setUsername('Andrey')
            ->setPassword($this->encoder->encodePassword($applicant, '321'))
            ->setEnabled(true)
            ->setEmail('tset@liam.eg')
            ->setRoles(['ROLE_APPLICANT']);

        $manager->persist($applicant);
        $manager->flush();
    }
}