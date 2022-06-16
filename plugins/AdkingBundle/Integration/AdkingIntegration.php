<?php

namespace MauticPlugin\AdkingBundle\Integration;

use Mautic\IntegrationsBundle\Integration\ConfigurationTrait;
use Mautic\PluginBundle\Integration\AbstractIntegration;

// use Mautic\IntegrationsBundle\Integration\Interfaces\BasicInterface;

class AdkingIntegration extends AbstractIntegration
{
    use ConfigurationTrait;
    public const NAME         = 'adking';
    public const DISPLAY_NAME = 'Adking';

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName(): string
    {
        return self::DISPLAY_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getIcon(): string
    {
        return 'plugins/AdkingBundle/Assets/img/test-tube-blood.png';
    }

    public function getAuthenticationType()
    {
        return 'key';
    }
}
