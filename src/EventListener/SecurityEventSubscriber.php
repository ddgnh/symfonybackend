<?php

declare(strict_types=1);

namespace App\EventListener;

use App\SharedAuthLibrary\Security\User;
use DateTimeImmutable;
use JetBrains\PhpStorm\ArrayShape;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SecurityEventSubscriber implements EventSubscriberInterface
{
    #[ArrayShape([Events::JWT_CREATED => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            Events::JWT_CREATED => 'onJwtCreated',
        ];
    }

    public function onJwtCreated(JWTCreatedEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        $payload = $event->getData();
        $payload['id'] = $user->getId();
        $payload['roles'] = $user->getRoles();
        $payload['username'] = $user->getUsername();
        $payload['exp'] = (new DateTimeImmutable())->getTimestamp() + 86400;

        $event->setData($payload);
    }
}
