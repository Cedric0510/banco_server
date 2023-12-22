<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use App\Entity\User;

class JWTEventListener
{
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $user = $event->getUser();

        // S'assurer que l'utilisateur est une instance de votre classe User
        if (!$user instanceof User) {
            return;
        }

        $payload = $event->getData();

        // Ajouter l'ID de l'utilisateur au payload
        $payload['user_id'] = $user->getId();

        $event->setData($payload);
    }
}
