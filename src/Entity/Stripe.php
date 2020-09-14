<?php

namespace App\Entity;

use App\Repository\StripeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StripeRepository::class)
 */
class Stripe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripeCustomerId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripeProductId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripeSubscriptionId;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User\User", inversedBy="stripe")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Assert\Type(type="App\Entity\User\User")
     * @Groups({"user"})
     */
    private $user;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getStripeCustomerId()
    {
        return $this->stripeCustomerId;
    }

    /**
     * @param string|null $stripeCustomerId
     * @return $this
     */
    public function setStripeCustomerId(?string $stripeCustomerId): self
    {
        $this->stripeCustomerId = $stripeCustomerId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStripeProductId(): ?string
    {
        return $this->stripeProductId;
    }

    /**
     * @param string|null $stripeProductId
     * @return $this
     */
    public function setStripeProductId(?string $stripeProductId): self
    {
        $this->stripeProductId = $stripeProductId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStripeSubscriptionId(): ?string
    {
        return $this->stripeSubscriptionId;
    }

    /**
     * @param string|null $stripeSubscriptionId
     * @return $this
     */
    public function setStripeSubscriptionId(?string $stripeSubscriptionId): self
    {
        $this->stripeSubscriptionId = $stripeSubscriptionId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $user
     * @return $this
     */
    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }
}
