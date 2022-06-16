<?php

namespace MauticPlugin\ExampleBundle\Integration\Support;

use Mautic\IntegrationsBundle\Integration\ConfigurationTrait;
use Mautic\IntegrationsBundle\Integration\Interfaces\AuthenticationInterface;
use MauticPlugin\ExampleBundle\Integration\KateIntegration;
use Symfony\Component\HttpFoundation\Request;

class AuthSupport implements AuthenticationInterface
{
    use ConfigurationTrait;

    // /**
    //  * @var Client
    //  */
    // private $client;

    // public function __construct(Client $client)
    // {
    //     $this->client = $client;
    // }

    public function getName(): string
    {
        return KateIntegration::NAME;
    }

    public function getDisplayName(): string
    {
        return KateIntegration::DISPLAY_NAME;
    }

    /**
     * Returns true if the integration has already been authorized with the 3rd party service.
     */
    public function isAuthenticated(): bool
    {
        $apiKeys = $this->getIntegrationConfiguration()->getApiKeys();

        return !empty($apiKeys['access_token']) && !empty($apiKeys['refresh_token']);
    }

    /**
     * Authenticate and obtain the access token.
     */
    public function authenticateIntegration(Request $request): string
    {
        // $code = $request->query->get('code');

        // $this->client->authenticate($code);

        return 'Success!';
    }
}
