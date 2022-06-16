<?php

declare(strict_types=1);

namespace MauticPlugin\AdkingBundle\Connection;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Mautic\CoreBundle\Helper\CacheStorageHelper;
use Mautic\IntegrationsBundle\Auth\Provider\BasicAuth\HttpFactory;
use Mautic\IntegrationsBundle\Exception\PluginNotConfiguredException;
use MauticPlugin\AdkingBundle\Integration\AdkingIntegration;
use MauticPlugin\AdkingBundle\Integration\Config;
use Monolog\Logger;
use Symfony\Component\Routing\Router;

class Client
{
    private $apiUrl;

    /**
     * @var HttpFactory
     */
    private $httpFactory;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var \Mautic\CoreBundle\Helper\CacheStorageHelper
     */
    private $cacheProvider;

    /**
     * @var Config
     */
    private $config;

    public function __construct(
        HttpFactory $httpFactory,
        CacheStorageHelper $cacheProvider,
        Router $router,
        Logger $logger,
        Config $config
    ) {
        $this->httpFactory      = $httpFactory;
        $this->logger           = $logger;
        $this->router           = $router;
        $this->cacheProvider    = $cacheProvider;
        $this->config           = $config;
    }

    public function validateCredentials(string $apiUrl, string $key, string $secret): bool
    {
        try {
            $client   = $this->httpFactory->getClient(new Credentials($key, $secret));
            $response = $client->get($apiUrl.'/v1/my-api-end-point');
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $this->logger->error(
                sprintf(
                    '%s: Error validating API credential: %s',
                    AdkingIntegration::DISPLAY_NAME,
                    $response->getReasonPhrase()
                )
            );
        }

        if (200 !== (int) $response->getStatusCode()) {
            return false;
        }

        return true;
    }

    /**
     * Get the client Client object from HttpFactory.
     */
    private function getClient(): ClientInterface
    {
        $this->getApiHost();

        $credentials = $this->getCredentials();

        return $this->httpFactory->getClient($credentials);
    }

    /**
     * Get the credentials object.
     */
    private function getCredentials(): Credentials
    {
        if (!$this->config->isConfigured()) {
            throw new PluginNotConfiguredException();
        }

        $apiKeys = $this->config->getApiKeys();

        return new Credentials($apiKeys['key'], $apiKeys['secret']);
    }

    private function getApiHost(): void
    {
        $apiKeys      = $this->config->getApiKeys();
        $this->apiUrl =  $apiKeys['host'];
    }

    public function get(string $path, ?array $params = [])
    {
        try {
            $client   = $this->getClient();
            $url      = sprintf('%s/%s', $this->apiUrl, $path);

            $cacheKey = str_replace(['/', '?', '&', '='], '_', $path);
            $data     = $this->cacheProvider->get($cacheKey);

            if (empty($data)) {
                $response = $client->get($url);
                if (200 === (int) $response->getStatusCode()) {
                    $content = $response->getBody()->getContents();
                    // Get and polish the content and save it in cache.
                    // Refresh the cache with the data just fetched.
                    $this->cacheProvider->set($cacheKey, $data, 60);
                }
            }

            return $data ?? [];
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $this->logger->error(
                sprintf(
                    '%s: Error fetching Playbook List: %s',
                    AdkingIntegration::DISPLAY_NAME,
                    $response->getReasonPhrase()
                )
            );
        }
    }
}
