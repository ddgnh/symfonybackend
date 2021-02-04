<?php

declare(strict_types=1);

namespace App\SharedAuthLibrary\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private string $id;

    private string $username;

    private array $roles;

    private string $password;

    private string $salt;

    public function __construct(
        string $id,
        string $username,
        array $roles,
        string $password = '',
        string $salt = '',
    ) {
        $this->id = $id;
        $this->roles = $roles;
        $this->username = $username;
        $this->password = $password;
        $this->salt = $salt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
