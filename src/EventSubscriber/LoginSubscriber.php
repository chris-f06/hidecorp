<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSubscriber implements EventSubscriberInterface
{
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function onLogin()
    {
        $user = $this->security->getUser();
        
        $user->setLastLoginAt(new \DateTime());
        $this->entityManager->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            LoginSuccessEvent::class => 'onLogin'
        ];
    }
}
