<?php

namespace App\Event;

use App\Entity\Chat;
use App\Entity\User\User;
use Symfony\Contracts\EventDispatcher\Event;

class ChatMessagesEvent extends Event
{
    /** @var User */
    private $userApplicant;

    /** @var User */
    private $userEmployer;

    /** @var User */
    private $activeUser;

    /**
     * ChatMessagesEvent constructor.
     * @param User $userApplicant
     * @param User $userEmployer
     * @param User $activeUser
     */
    public function __construct(User $userApplicant, User $userEmployer, User $activeUser)
    {
        $this->userApplicant = $userApplicant;
        $this->userEmployer  = $userEmployer;
        $this->activeUser    = $activeUser;
    }

    /**
     * @return User
     */
    public function getUserApplicant(): User
    {
        return $this->userApplicant;
    }

    /**
     * @return User
     */
    public function getUserEmployer(): User
    {
        return $this->userEmployer;
    }

    /**
     * @return User
     */
    public function getActiveUser(): User
    {
        return $this->activeUser;
    }
}