<?php

return [
    'name'        => 'AA TEST PLUGIN',
    'description' => 'Enables integration for integration using API params',
    'version'     => '1.0.0',
    'author'      => 'Kate',
    'routes'      => [
        // 'public' => [
        //     'mautic_integration.adking.webhook' => [
        //         'path'       => '/plugin/adking/webhook',
        //         'controller' => 'AdkingBundle:Adking:webhook', /* BundleName:ControllerClass:controllerMethod */
        //         'method'     => 'POST',
        //     ],
        // ],
    ],
    'services' => [
        'events' => [
            'mautic_integration.adking.lead.subscriber' => [
                'class'     => \MauticPlugin\AdkingBundle\EventListener\LeadSubscriber::class,
                'arguments' => [
                    'mautic.helper.integration',
                    'monolog.logger.mautic',
                ],
            ],
            'mautic_integration.adking.company.subscriber' => [
                'class'     => \MauticPlugin\AdkingBundle\EventListener\CompanySubscriber::class,
                'arguments' => [
                    'mautic.helper.integration',
                    'monolog.logger.mautic',
                ],
            ],
        ],
        'integrations' => [
            // Basic definitions with name, display name and icon
            'mautic.integration.adking' => [
                'class' => \MauticPlugin\AdkingBundle\Integration\AdkingIntegration::class,
                'tags'  => [
                    'mautic.integration',
                    'mautic.basic_integration',
                ],
            ],
            // Provides the form types to use for the configuration UI
            'mautic.integration.adking.configuration' => [
                'class'     => \MauticPlugin\AdkingBundle\Integration\Support\ConfigSupport::class,
                'tags'      => [
                    'mautic.config_integration',
                ],
            ],
        ],
        // 'other' => [
        //     'adking.integration.config' => [
        //         'class'     => \MauticPlugin\AdkingBundle\Integration\Config::class,
        //         'arguments' => [
        //           'mautic.integrations.helper',
        //         ],
        //     ],
        //     'adking.connection.client' => [
        //         'class'     => \MauticPlugin\AdkingBundle\Connection\Client::class,
        //         'arguments' => [
        //             'mautic.integrations.auth_provider.basic_auth',
        //             'mautic.helper.cache_storage',
        //             'router',
        //             'monolog.logger.mautic',
        //             'adking.integration.config',
        //         ],
        //     ],
        //     'adking.connection.client.consumer' => [
        //         'class'     => \MauticPlugin\AdkingBundle\Connection\ApiConsumer::class,
        //         'arguments' => [
        //             'mautic.helper.cache_storage',
        //             'monolog.logger.mautic',
        //             'adking.connection.client',
        //             'adking.integration.config',
        //         ],
        //     ],
        // ]
    ],
];
