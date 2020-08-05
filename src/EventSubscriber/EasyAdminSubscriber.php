<?php

namespace App\EventSubscriber;

use App\Entity\User\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class EasyAdminSubscriber
 * @package App\EventSubscriber
 */
class EasyAdminSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    /**
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @return array|\string[][]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setEncryptPassword'],
        ];
    }

    /**
     * @param BeforeEntityPersistedEvent $event
     */
    public function setEncryptPassword(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

        $password = $this->encoder->encodePassword($entity, $entity->getPassword());
        $entity->setPassword($password);
    }
}