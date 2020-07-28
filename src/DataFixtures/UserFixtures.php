<?php

namespace App\DataFixtures;

use App\Entity\User;
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
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('user')
        ->setEmail('user@email.org')
        ->addRole('ROLE_USER')
        ->setEnabled(true)
        ->setPassword($this->encoder->encodePassword($user, 'user'));

        $admin = new User();
        $admin->setUsername('admin')
        ->setEmail('admin@email.org')
        ->setPassword($this->encoder->encodePassword($admin, 'admin'))
        ->addRole('ROLE_ADMIN')
        ->setEnabled(true);

        $manager->persist($user);
        $manager->persist($admin);

        $manager->flush();
    }
}