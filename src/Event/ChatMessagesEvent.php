<?php

namespace App\Event;

use App\Entity\User\User;
use Symfony\Contracts\EventDispatcher\Event;

class ChatMessagesEvent extends Event
{
    public const NAME = 'chat.messages.event';

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $room;

    /**
     * ChatMessagesEvent constructor.
     *
     * @param User   $user
     * @param string $room
     */
    public function __construct(User $user, string $room)
    {
        $this->user = $user;
        $this->room = $room;
    }

    /**
     * @return string
     */
    public function getRoom(): string
    {
        return $this->room;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}