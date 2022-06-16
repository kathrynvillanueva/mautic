<?php

namespace MauticPlugin\ExampleBundle\Connection;

use Mautic\IntegrationsBundle\Auth\Provider\BasicAuth\CredentialsInterface;

class Credentials implements CredentialsInterface
{
    private $userName;
    private $userPassword;

    public function __construct(string $userName, string $userPassword)
    {
        $this->userName     = $userName;
        $this->userPassword = $userPassword;
    }

    public function getUsername(): ?string
    {
        return $this->userName;
    }

    public function getPassword(): ?string
    {
        return $this->userPassword;
    }
}
