<?php

namespace App\Sockets;

use App\Entity\User\User;
use App\Repository\ChatRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * Class Chat
 * @package App\Sockets
 */
class Chat implements MessageComponentInterface
{
    protected $clients;
    private $userInfo;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ChatRepository
     */
    private $chatRepository;

    /**
     * Chat constructor.
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     * @param ChatRepository $chatRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        ChatRepository $chatRepository
    ) {
        $this->clients = new \SplObjectStorage;
        $this->userInfo = [];
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->chatRepository = $chatRepository;
    }

    function onOpen(ConnectionInterface $conn): void
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    function onMessage(ConnectionInterface $from, $msg): void
    {
        $data = json_decode($msg, false);

        if (property_exists($data, 'connectedUserId')) {
            $this->userInfo[$data->connectedUserId] = $from->resourceId;
        }

        if (property_exists($data, 'to')) {
//            /** @var User $user */
//            $user = $this->userRepository->find($data->userId);
//
//            $chat = new \App\Entity\Chat();
//            $chat->setMessage($data->message)
//                ->setUserFrom($user)
//                ->setUserTo($data->to)
//                ->setSendDate(new \DateTime());
//            $user->addChat($chat);

            if (array_key_exists($data->to, $this->userInfo)) {
                foreach ($this->clients as $client) {
//                    if ($client->resourceId === $this->userInfo[$data->to]) {
                        $client->send($msg);
//                        $chat->setViewed(true);
                }
            }
        }

//            $this->em->persist($user);
//            $this->em->persist($chat);
//            $this->em->flush();
    }


    function onClose(ConnectionInterface $conn): void
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    function onError(ConnectionInterface $conn, \Exception $e): void
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
