<?php

declare(strict_types=1);

namespace App\SharedAuthLibrary\Security;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JwtUserProvider implements UserProviderInterface
{
    private JwtPayloadContainer $jwtPayloadContainer;

    public function __construct(JwtPayloadContainer $jwtPayloadContainer)
    {
        $this->jwtPayloadContainer = $jwtPayloadContainer;
    }

    #[Pure] public function loadUserByUsername($username): User
    {
        $payload = $this->jwtPayloadContainer->getPayload();
        dump($payload);

        return new User($payload['id'], $payload['email'], $payload['roles']);
    }

    public function refreshUser(UserInterface $user): User
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', \get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class): bool
    {
        return User::class === $class;
    }
}
