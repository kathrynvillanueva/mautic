<?php

return [
    'name'            => 'TEST PLUGIN 123',
    'description'     => 'installed 2',
    'author'          => 'Kate',
    'version'         => '1.0.0',
    'services'        => [
        'integrations' => [
            'mautic.integration.kate' => [
                'class' => \MauticPlugin\ExampleBundle\Integration\KateIntegration::class,
                'tags'  => [
                    'mautic.integration',
                    'mautic.basic_integration',
                ],
            ],
            'mautic.integration.kate.authentication' => [
                'class'     => \MauticPlugin\ExampleBundle\Integration\Support\AuthSupport::class,
                'tags'      => [
                    'mautic.auth_integration',
                ],
            ],
            'mautic.integration.kate.configuration' => [
                'class' => \MauticPlugin\ExampleBundle\Integration\Support\ConfigSupport::class,
                'tags'  => [
                    'mautic.config_integration',
                ],
            ],
        ],
        'other' => [
            'kate.integration.config' => [
                'class'     => \MauticPlugin\ExampleBundle\Integration\Config::class,
                'arguments' => [
                  'mautic.integrations.helper',
                ],
            ],
            'kate.connection.client' => [
                'class'     => \MauticPlugin\ExampleBundle\Connection\Client::class,
                'arguments' => [
                    'mautic.integrations.auth_provider.basic_auth',
                    'mautic.helper.cache_storage',
                    'router',
                    'monolog.logger.mautic',
                    'kate.integration.config',
                ],
            ],
            'kate.connection.client.consumer' => [
                'class'     => \MauticPlugin\ExampleBundle\Connection\ApiConsumer::class,
                'arguments' => [
                    'mautic.helper.cache_storage',
                    'monolog.logger.mautic',
                    'kate.connection.client',
                    'kate.integration.config',
                ],
            ],
        ],
    ],
];
