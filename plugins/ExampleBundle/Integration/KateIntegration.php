<?php

namespace MauticPlugin\ExampleBundle\Integration;

use Mautic\IntegrationsBundle\Integration\BasicIntegration;
use Mautic\IntegrationsBundle\Integration\Interfaces\IntegrationInterface;
use Mautic\IntegrationsBundle\Integration\Interfaces\SyncInterface;
use Mautic\IntegrationsBundle\Sync\DAO\Mapping\MappingManualDAO;
use Mautic\IntegrationsBundle\Sync\DAO\Mapping\ObjectMappingDAO;
use Mautic\IntegrationsBundle\Sync\SyncDataExchange\Internal\Object\Contact;
use Mautic\IntegrationsBundle\Sync\SyncDataExchange\SyncDataExchangeInterface;

class KateIntegration extends BasicIntegration implements IntegrationInterface, SyncInterface
{
    // use ConfigurationTrait;
    const NAME                     = 'kate';
    const DISPLAY_NAME             = 'Kate';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getDisplayName(): string
    {
        return self::DISPLAY_NAME;
    }

    public function getIcon(): string
    {
        return 'plugins/ExampleBundle/Assets/img/spongebob.png';
    }

    public function getAuthenticationType()
    {
        return 'key';
    }

    public function getSyncDataExchange(): SyncDataExchangeInterface
    {
        return $this->syncDataExchange;
    }

    public function getMappingManual(): MappingManualDAO
    {
        // Generate mapping manual that will be passed to the sync service. This instructs the sync service how to map Mautic fields to integration fields
        $mappingManual = new MappingManualDAO(self::NAME);

        // Each object like lead, contact, user, company, account, etc, will need it's own ObjectMappingDAO
        // In this example, Mautic's Contact object is mapped to the Example's Lead object
        $leadObjectMapping = new ObjectMappingDAO(
            Contact::NAME,
            ExampleSyncDataExchange::OBJECT_LEAD
        );
        $mappingManual->addObjectMapping($leadObjectMapping);

        // Get field mapping as configured in Mautic's integration config
        $mappedFields = $this->getConfiguredFieldMapping();

        foreach ($mappedFields as $integrationField => $mauticField) {
            // In this case, we're just adding each field to each of the objects
            // Of course, other integrations may need more logic

            // Sync bidirectionally by default but also can use ObjectMappingDAO::SYNC_TO_MAUTIC or ObjectMappingDAO::SYNC_TO_INTEGRATION

            if ('email' === $mauticField) {
                // Set email as a required field so that it maps a value regardless if changed
                $leadObjectMapping->addFieldMapping($mauticField, $integrationField, ObjectMappingDAO::SYNC_BIDIRECTIONALLY, true);
            } else {
                $leadObjectMapping->addFieldMapping($mauticField, $integrationField);
            }
        }

        return $mappingManual;
    }

    /**
     * Likely will get this mapping out of the Integration's settings.
     *
     * @return array
     */
    private function getConfiguredFieldMapping()
    {
        return [
            'firstname'  => 'firstname',
            'lastname'   => 'lastname',
            'email'      => 'email',
        ];
    }
}