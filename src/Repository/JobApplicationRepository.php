<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Job;
use App\Entity\JobApplication;
use App\Entity\Resume;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Internal\CommitOrderCalculator;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JobApplication|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobApplication|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobApplication[]    findAll()
 * @method JobApplication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobApplicationRepository extends ServiceEntityRepository
{
    /**
     * JobApplicationRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobApplication::class);
    }

    /**
     * @param Job    $job
     * @param Resume $resume
     * @return QueryBuilder
     */
    public function checkCoincidence(Job $job, Resume $resume): QueryBuilder
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.job = :job')
            ->andWhere('j.resume = :resume')
            ->setParameter('job', $job)
            ->setParameter('resume', $resume)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param User $user
     * @return int|mixed|string
     */
    public function getJobApplicationsByUser(User $user)
    {
        return $this->createQueryBuilder('j')
            ->select('j')
            ->innerJoin('j.resume', 'r')
            ->andWhere('r.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Company $company
     * @return int|mixed|string
     */
    public function getJobApplicationsByCompany(Company $company)
    {
        return $this->createQueryBuilder('j')
            ->select('j')
            ->innerJoin('j.job', 'job')
            ->andWhere('job.company = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getResult();
    }
}
