<?php

namespace MauticPlugin\AdkingBundle\EventListener;

use Mautic\LeadBundle\Entity\Company;
use Mautic\LeadBundle\Event\CompanyEvent;
use Mautic\LeadBundle\LeadEvents;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\AdkingBundle\Integration\AdkingIntegration;
use MauticPlugin\MauticCrmBundle\Integration\Pipedrive\AbstractPipedrive;
use MauticPlugin\MauticCrmBundle\Integration\PipedriveIntegration;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CompanySubscriber extends AbstractPipedrive implements EventSubscriberInterface
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
            LeadEvents::COMPANY_POST_SAVE  => ['onCompanyPostSave', 0],
            LeadEvents::COMPANY_PRE_DELETE => ['onCompanyPreDelete', 10],
        ];
    }

    /**
     * @param LeadEvent $event
     */
    public function onCompanyPostSave(CompanyEvent $event)
    {
        $company = $event->getCompany();
        $this->logger->info('post save');
        $this->mappedCompany($company);

        /** @var AdkingIntegration $integrationObject */
        $integrationObject = $this->integrationHelper->getIntegrationObject(AdkingIntegration::NAME);
        if (false === $integrationObject) {
            return;
        }
    }

    /**
     * @param LeadEvent $event
     */
    public function onCompanyPreDelete(CompanyEvent $event)
    {
        $company = $event->getCompany();
        $this->logger->info('pre delete');
        $this->mappedCompany($company);

        /** @var AdkingIntegration $integrationObject */
        $integrationObject = $this->integrationHelper->getIntegrationObject(AdkingIntegration::NAME);
        if (false === $integrationObject) {
            return;
        }
    }

    private function mappedCompany(Company $company)
    {
        $integration = new AdkingIntegration();
        // $integration = new PipedriveIntegration;
        $this->logger->info(var_export($company, true));
        $this->logger->info(var_export($integration->getIntegrationSettings(), true));

        return $company;
    }
}
