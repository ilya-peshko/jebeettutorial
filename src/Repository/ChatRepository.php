<?php

namespace App\Repository;

use App\Entity\Chat;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Chat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chat[]    findAll()
 * @method Chat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    /**
     * @param Int $id
     *
     * @return array
     */
    public function getUniqueChatsByEmployer(Int $id): array
    {
        return $this->createQueryBuilder('chat')
            ->select('user.id, user.uuid, user.username')
            ->innerJoin('chat.userFrom', 'user')
            ->distinct()
            ->where('chat.userTo = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $users
     *
     * @return array
     */
    public function getMessagesByUsersId(Array $users): array
    {
        return $this->createQueryBuilder('chat')
            ->where('chat.userTo IN (:users)')
            ->andWhere('chat.userFrom IN (:users)')
            ->setParameter('users', $users)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $userToId
     * @param User $userFrom
     * @return int|mixed|string
     */
    public function getNotViewedMessagesByUsers($userToId, User $userFrom)
    {
        return $this->createQueryBuilder('chat')
            ->where('chat.userTo = :id')
            ->andWhere('chat.viewed = 0')
            ->andWhere('chat.userFrom = :user')
            ->setParameter('id', $userToId)
            ->setParameter('user', $userFrom)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $applicant
     * @param User $employer
     * @throws NonUniqueResultException
     *
     * @return int|mixed|string|null
     */
    public function checkRoomByUsers(User $applicant, User $employer)
    {
        $rooms = $this->createQueryBuilder('chat')
                ->select('chat.room')
                ->where('chat.user = :employer')
                ->setParameter('employer', $employer);

        return $this->createQueryBuilder('chat')
            ->where('chat.user = :applicant')
            ->andWhere('chat.room IN (:rooms)')
            ->setParameter('applicant', $applicant)
            ->setParameter('rooms', $rooms->getDQL())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $room
     * @return int|mixed|string|null
     */
    public function getMessagesByRoom($room)
    {
        return $this->createQueryBuilder('chat')
            ->where('chat.room = :room')
            ->setParameter('room', $room);
    }
}
