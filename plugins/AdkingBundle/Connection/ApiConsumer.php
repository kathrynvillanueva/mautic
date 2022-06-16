<?php

namespace MauticPlugin\AdkingBundle\Connection;

use Mautic\CoreBundle\Helper\CacheStorageHelper;
use MauticPlugin\AdkingBundle\Integration\Config;
use Monolog\Logger;

class ApiConsumer
{
    /**
     * @var \Mautic\CoreBundle\Helper\CacheStorageHelper
     */
    private $cacheProvider;
    /**
     * @var \Monolog\Logger
     */
    private $logger;
    /**
     * @var \MauticPlugin\AdkingBundle\Connection\Client
     */
    private $client;
    /**
     * @var \MauticPlugin\AdkingBundle\Integration\Config
     */
    private $config;

    public function __construct(
        CacheStorageHelper $cacheProvider,
        Logger $logger,
        Client $client,
        Config $config
    ) {
        $this->cacheProvider    = $cacheProvider;
        $this->logger           = $logger;
        $this->client           = $client;
        $this->config           = $config;
    }

    /**
     * Fetch the data from API endpoint.
     */
    public function getSubscribers()
    {
        $users = $this->client->get('v2/api/fetch-dummy-users');
        // Polish and manipulate the data.
        return $users;
    }
}
