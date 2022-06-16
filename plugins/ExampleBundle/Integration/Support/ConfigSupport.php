<?php

namespace MauticPlugin\ExampleBundle\Integration\Support;

use Mautic\IntegrationsBundle\Integration\ConfigurationTrait;
use Mautic\IntegrationsBundle\Integration\DefaultConfigFormTrait;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormAuthInterface;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormInterface;
use MauticPlugin\ExampleBundle\Form\Type\ConfigAuthType;
use MauticPlugin\ExampleBundle\Integration\KateIntegration;

class ConfigSupport extends KateIntegration implements ConfigFormInterface, ConfigFormAuthInterface
{
    use // ConfigurationTrait,
        DefaultConfigFormTrait;

    // public function getDisplayName(): string
    // {
    //     return KateIntegration::DISPLAY_NAME;
    // }

    // public function getName(): string
    // {
    //     return KateIntegration::DISPLAY_NAME;
    // }

    /**
     * Return a custom Symfony form field type class that will be used on the Enabled/Auth tab.
     * This should include things like API credentials, URLs, etc. All values from this form fields
     * will be encrypted before being persisted.
     *
     * @see https://symfony.com/doc/2.8/form/create_custom_field_type.html#defining-the-field-type
     */
    public function getAuthConfigFormName(): string
    {
        return ConfigAuthType::class;
    }

    // public function getIntegrationConfiguration(): Integration
    // {
    //     return KateIntegration::class;
    // }

    // public function getName()
    // {
    //     return '';
    // }

    // public function hasIntegrationConfiguration()
    // {
    //     return '';
    // }

    // public function getIntegrationConfiguration()
    // {
    //     return '';
    // }

    // public function setIntegrationConfiguration()
    // {
    //     return '';
    // }
}
