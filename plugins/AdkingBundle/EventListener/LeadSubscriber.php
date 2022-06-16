<?php

namespace MauticPlugin\AdkingBundle\EventListener;

use Mautic\LeadBundle\Entity\Lead;
use Mautic\LeadBundle\Event as Events;
use Mautic\LeadBundle\LeadEvents;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\AdkingBundle\Integration\AdkingIntegration;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LeadSubscriber implements EventSubscriberInterface
{
    /**
     * @var IntegrationHelper
     */
    private $integrationHelper;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(
        IntegrationHelper $integrationHelper,
        Logger $logger
    ) {
        $this->integrationHelper = $integrationHelper;
        $this->logger            = $logger;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            LeadEvents::LEAD_POST_SAVE      => ['onLeadPostSave', 0],
            LeadEvents::LEAD_PRE_DELETE     => ['onLeadPostDelete', 255],
            LeadEvents::LEAD_COMPANY_CHANGE => ['onLeadCompanyChange', 0],
        ];
    }

    public function onLeadPostSave(Events\LeadEvent $event)
    {
        $lead = $event->getLead();
        $this->logger->info('lead post save');
        $this->mappedLead($lead);
        if ($lead->isAnonymous()) {
            // Ignore this contact
            return;
        }
        // if ($lead->getEventData('pipedrive.webhook')) {
        //     // Don't export what was just imported
        //     return;
        // }
        /** @var AdkingIntegration $integrationObject */
        $integrationObject = $this->integrationHelper->getIntegrationObject(AdkingIntegration::NAME);
        if (false === $integrationObject) {
            return;
        }
        // $this->leadExport->setIntegration($integrationObject);

        $changes = $lead->getChanges(true);
        // if (!empty($changes['dateIdentified'])) {
        //     $this->leadExport->create($lead);
        // } else {
        //     $this->leadExport->update($lead);
        // }
    }

    public function onLeadPostDelete(Events\LeadEvent $event)
    {
        $lead = $event->getLead();
        $this->logger->info('lead post delete');
        $this->mappedLead($lead);
        // if ($lead->getEventData('pipedrive.webhook')) {
        //     // Don't export what was just imported
        //     return;
        // }

        /** @var AdkingIntegration $integrationObject */
        $integrationObject = $this->integrationHelper->getIntegrationObject(AdkingIntegration::NAME);
        if (false === $integrationObject) {
            return;
        }
        // $this->leadExport->setIntegration($integrationObject);
        // $this->leadExport->delete($lead);
    }

    public function onLeadCompanyChange(Events\LeadChangeCompanyEvent $event)
    {
        $lead = $event->getLead();
        $this->logger->info('lead company change');
        $this->mappedLead($lead);
        // if ($lead->getEventData('pipedrive.webhook')) {
        //     // Don't export what was just imported
        //     return;
        // }

        /** @var AdkingIntegration $integrationObject */
        $integrationObject = $this->integrationHelper->getIntegrationObject(AdkingIntegration::NAME);
        if (false === $integrationObject) {
            return;
        }
        // $this->leadExport->setIntegration($integrationObject);
        // $this->leadExport->update($lead);
    }

    private function mappedLead(Lead $lead)
    {
        $this->logger->info(var_export($lead, true));

        return $lead;
    }
}
