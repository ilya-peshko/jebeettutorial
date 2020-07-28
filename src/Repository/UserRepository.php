<?php

namespace App\Repository;

use App\Entity\AbstractUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * @method AbstractUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbstractUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbstractUser[]    findAll()
 * @method AbstractUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbstractUser::class);
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
}
