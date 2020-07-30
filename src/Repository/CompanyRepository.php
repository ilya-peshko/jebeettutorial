<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    /**
     * @return int|mixed|string
     */
    public function getAllCompanies()
    {
        return $this->createQueryBuilder('c')
            ->getQuery()
            ->getResult();
    }
    /**
     * @param int $id
     *
     * @throws NonUniqueResultException
     *
     * @return Company|null
     */
    public function findCompanyById(int $id) : ?Company
    {
        return $this->createQueryBuilder('c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return int|mixed|string
     */
    public function findCompanyActiveJobs()
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->innerJoin('c.jobs', 'j')
            ->where('j.expiresAt > :date')
            ->andWhere('j.activated = :activated')
            ->setParameter('date', new \DateTime())
            ->setParameter('activated', true)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Company[] Returns an array of Company objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Company
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
