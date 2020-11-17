<?php

namespace App\EventSubscriber;

use App\Entity\User\User;
use App\Event\ChatMessagesEvent;
use App\Repository\ChatRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ChatViewedSubscriber
 * @package App\EventSubscriber
 */
class ChatViewedSubscriber implements EventSubscriberInterface
{
    /**
     * @var ChatRepository
     */
    private $chatRepository;

    public function __construct(ChatRepository $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }

    /**
     * @param ChatMessagesEvent $event
     */
    public function onChatMessagesEvent(ChatMessagesEvent $event): void
    {
        $messages = $event->getMessages();

        $messages = $this->chatRepository->getNotViewedMessagesByUsers($id, $userFrom);
        if ($messages) {
            foreach ($messages as $message) {
                $message->setViewed(true);
            }
        }

    }

    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'chat.messages.event' => 'onChatMessagesEvent',
        ];
    }
}
