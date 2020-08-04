<?php

namespace App\Repository;

use App\Entity\Resume;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Resume|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resume|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resume[]    findAll()
 * @method Resume[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResumeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Resume::class);
    }

    /**
     * @param User $user
     * @param null $request
     * @return array
     */
    public function getAllResumesByUser(User $user, $request = null): array
    {
        $resumes = $this->createQueryBuilder('r')
            ->andWhere('r.user = :applicant')
            ->setParameter('applicant', $user);

        if ($request) {
            $resumes->andWhere('r.title LIKE :request')
                ->orWhere('r.cityOfResidence LIKE :request')
                ->orWhere('r.name LIKE :request')
                ->orWhere('r.surname LIKE :request')
                ->orWhere('r.aboutMe LIKE :request')
                ->setParameter('request', "%$request%");
        }

        return $resumes->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Resume[] Returns an array of Resume objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Resume
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
