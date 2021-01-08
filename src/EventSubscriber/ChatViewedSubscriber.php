<?php

namespace App\EventSubscriber;

use App\Entity\Chat;
use App\Event\ChatMessagesEvent;
use App\Repository\ChatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ChatViewedSubscriber
 *
 * @package App\EventSubscriber
 */
class ChatViewedSubscriber implements EventSubscriberInterface
{
    /**
     * @var ChatRepository
     */
    private $chatRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ChatRepository $chatRepository, EntityManagerInterface $em)
    {
        $this->chatRepository = $chatRepository;
        $this->em             = $em;
    }

    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ChatMessagesEvent::NAME => 'onChatMessagesEvent',
        ];
    }

    /**
     * @param ChatMessagesEvent $event
     */
    public function onChatMessagesEvent(ChatMessagesEvent $event): void
    {
        $messages = $this->chatRepository->getNotViewedMessagesByUsers($event->getRoom(), $event->getUser());
        if ($messages) {
            /** @var Chat $message */
            foreach ($messages as $message) {
                $message->setViewed(true);
                $this->em->persist($message);
            }
            $this->em->flush();
        }
    }
}
