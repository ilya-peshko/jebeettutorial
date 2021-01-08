<?php

namespace App\Sockets;

use App\Entity\User\User;
use App\Event\ChatMessagesEvent;
use App\Repository\ChatRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use SplObjectStorage;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Chat
 * @package App\Sockets
 */
class Chat implements MessageComponentInterface
{
    /**
     * @var SplObjectStorage
     */
    protected $clients;

    /**
     * @var array
     */
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
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Chat constructor.
     *
     * @param EntityManagerInterface   $em
     * @param UserRepository           $userRepository
     * @param ChatRepository           $chatRepository
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        ChatRepository $chatRepository,
        EventDispatcherInterface $dispatcher
    ) {
        $this->clients        = new SplObjectStorage;
        $this->userInfo       = [];
        $this->em             = $em;
        $this->userRepository = $userRepository;
        $this->chatRepository = $chatRepository;
        $this->dispatcher     = $dispatcher;
    }

    function onOpen(ConnectionInterface $conn): void
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        $count = count($this->clients);
        echo "New connection! ({$conn->resourceId})\n";
        echo "Count connections: {$count}\n";
    }

    function onMessage(ConnectionInterface $from, $msg): void
    {
        $numRecv = count($this->clients) - 1;
        echo sprintf(
            'Connection %d sending message "%s" to %d other connection%s' . "\n",
            $from->resourceId,
            $msg,
            $numRecv,
            $numRecv === 1 ? '' : 's'
        );

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }

        $data = json_decode($msg, true);
        if (array_key_exists('connectedUserId', $data)) {
            /** @var User $user */
            $user  = $this->userRepository->find($data['connectedUserId']);
            $event = new ChatMessagesEvent($user, $data['room']);
            $this->dispatcher->dispatch($event);
        }

//        echo "Message sanded\n";
//        if (property_exists($data, 'to')) {
//            /** @var User $user */
//            $user = $this->userRepository->find($data->userId);
//
//            $chat = new \App\Entity\Chat();
//            $chat->setMessage($data->message)
//                ->setUserFrom($user)
//                ->setUserTo($data->to)
//                ->setSendDate(new \DateTime());
//            $user->addChat($chat);
//
//            if (array_key_exists($data->to, $this->userInfo)) {
//                foreach ($this->clients as $client) {
//                    if ($client->resourceId === $this->userInfo[$data->to]) {
//                        $client->send($msg);
//                        $chat->setViewed(true);
//                }
//            }
//      }

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
