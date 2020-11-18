<?php

namespace App\Entity;

use App\Entity\User\User;
use App\Repository\ChatRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=ChatRepository::class)
 */
class Chat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="chats")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @var UuidInterface
     *
     * @ORM\Column(name="room", type="string")
     */
    private $room;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $sendDate;

    /**
     * @var bool
     * @ORM\Column(name="viewed", type="boolean")
     */
    private $viewed;

    /**
     * Chat constructor.
     */
    public function __construct()
    {
        $this->viewed = false;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoom(): string
    {
        return $this->room;
    }

    /**
     * @param string $room
     *
     * @return Chat
     */
    public function setRoom(string $room): Chat
    {
        $this->room = $room;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getSendDate(): ?DateTimeInterface
    {
        return $this->sendDate;
    }

    /**
     * @param DateTimeInterface|null $sendDate
     * @return $this
     */
    public function setSendDate(?DateTimeInterface $sendDate): self
    {
        $this->sendDate = $sendDate;

        return $this;
    }

    /**
     * @param $boolean
     * @return $this
     */
    public function setViewed($boolean): self
    {
        $this->viewed = (bool)$boolean;

        return $this;
    }

    /**
     * @return bool
     */
    public function isViewed(): bool
    {
        return $this->viewed;
    }
}
