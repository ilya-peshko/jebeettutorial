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
     * @param User $user
     *
     * @return array
     */
    public function getUniqueChatsByEmployer(User $user): array
    {
        $rooms = $this->createQueryBuilder('chat')
            ->select('chat.room')
            ->where('chat.user = :user')
            ->setParameter('user', $user)
            ->distinct()
            ->getQuery()
            ->getResult();

        $arrayRooms = [];
        foreach ($rooms as $room) {
            $arrayRooms[]= $room['room'];
        }
        $rooms = implode(',', $arrayRooms);

        return $this->createQueryBuilder('chat')
            ->where('chat.user != :user')
            ->andWhere('chat.room IN (:rooms)')
            ->setParameter('user', $user)
            ->setParameter('rooms', $rooms)
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getMessagesByUsers(User $user): array
    {
        return $this->createQueryBuilder('chat')
            ->where('chat.user = :user')
            ->setParameter('user', $user)
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
     *
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function checkRoomByUsers(User $applicant, User $employer)
    {
        $rooms = $this->createQueryBuilder('chat')
            ->select('chat.room')
            ->where('chat.user = :user')
            ->setParameter('user', $applicant)
            ->distinct()
            ->getQuery()
            ->getResult();

        $arrayRooms = [];
        foreach ($rooms as $room) {
            $arrayRooms[]= $room['room'];
        }
        $rooms = implode(',', $arrayRooms);

        $rooms = $this->createQueryBuilder('chat')
            ->where('chat.user = :user')
            ->andWhere('chat.room IN (:rooms)')
            ->setParameter('user', $employer)
            ->setParameter('rooms', $rooms)
            ->distinct()
            ->getQuery();

        return $rooms->getResult()[0];
    }

    /**
     * @param $room
     *
     * @return int|mixed|string|null
     */
    public function getMessagesByRoom($room)
    {
        return $this->createQueryBuilder('chat')
            ->where('chat.room = :room')
            ->setParameter('room', $room)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $room Room Id
     * @param User   $user User
     *
     * @return int|mixed|string|null
     */
    public function getUserMessagesInRoom(string $room, User $user)
    {
        return $this->createQueryBuilder('chat')
            ->where('chat.room = :room')
            ->andWhere('chat.user = :user')
            ->setParameter('room', $room)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $room Room Id
     * @param User   $user User
     *
     * @return int|mixed|string|null
     */
    public function getCompanionMessagesInRoom(string $room, User $user)
    {
        return $this->createQueryBuilder('chat')
            ->where('chat.room = :room')
            ->andWhere('chat.user != :user')
            ->setParameter('room', $room)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
