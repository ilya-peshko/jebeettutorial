<?php

namespace App\Repository;

use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $user
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function loadUserByUsername($user)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->orWhere('u.email = :email')
            ->setParameter('username', $user)
            ->setParameter('email', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getApplicants()
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles = :role')
            ->setParameter('role', 'ROLE_APPLICANT')
            ->getQuery()
            ->getResult();
    }
}
