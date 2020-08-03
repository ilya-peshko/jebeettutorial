<?php

namespace App\DataFixtures;

use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    /**
     * EmployerFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('Ilya')
            ->setPassword($this->encoder->encodePassword($user, '123'))
            ->setEnabled(1)
            ->setEmail('test@mail.ge')
            ->setRoles(['ROLE_EMPLOYER']);

        $manager->persist($user);

        $user_two = new User();
        $user_two->setUsername('Andy')
            ->setPassword($this->encoder->encodePassword($user_two, '123'))
            ->setEnabled(1)
            ->setEmail('applicant@mail.ge')
            ->setRoles(['ROLE_APPLICANT']);

        $manager->persist($user_two);

        $manager->flush();

        $this->addReference('user-one', $user);
        $this->addReference('user-applicant', $user_two);
    }
}
