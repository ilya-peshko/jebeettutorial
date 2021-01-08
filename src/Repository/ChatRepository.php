<?php

namespace App\Repository;

use App\Entity\Chat;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
            $arrayRooms[]= (string)($room['room']);
        }

        return $this->createQueryBuilder('chat')
            ->select(['chat.room', 'u.username'])
            ->where('chat.user != :user')
            ->andWhere('chat.room IN (:rooms)')
            ->setParameter('user', $user)
            ->setParameter('rooms', $arrayRooms)
            ->innerJoin('chat.user', 'u')
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getMessagesByUser(User $user): array
    {
        return $this->createQueryBuilder('chat')
            ->where('chat.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string$room
     * @param User $user
     *
     * @return int|mixed|string
     */
    public function getNotViewedMessagesByUsers(string $room, User $user)
    {
        return $this->createQueryBuilder('chat')
            ->where('chat.user != :user')
            ->andWhere('chat.viewed = 0')
            ->andWhere('chat.room = :room')
            ->setParameter('user', $user)
            ->setParameter('room', $room)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $applicant
     * @param User $employer
     *
     * @return int|mixed|string|null
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

        $rooms = $this->createQueryBuilder('chat')
            ->where('chat.user = :user')
            ->andWhere('chat.room IN (:rooms)')
            ->setParameter('user', $employer)
            ->setParameter('rooms', $arrayRooms)
            ->distinct()
            ->getQuery();

        return $rooms->getResult()[0] ?? null;
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

    /**
     * @param string $room Room Id
     *
     * @return int|mixed|string|null
     */
    public function checkNewRoom(string $room)
    {
        return $this->createQueryBuilder('chat')
            ->where('chat.room = :room')
            ->setParameter('room', $room)
            ->getQuery()
            ->getResult();
    }

}
