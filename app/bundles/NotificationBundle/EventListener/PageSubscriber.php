<?php

namespace Mautic\NotificationBundle\EventListener;

use Mautic\CoreBundle\Twig\Helper\AssetsHelper;
use Mautic\PageBundle\Event\PageDisplayEvent;
use Mautic\PageBundle\PageEvents;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PageSubscriber implements EventSubscriberInterface
{
    private \Mautic\CoreBundle\Twig\Helper\AssetsHelper $assetsHelper;

    private \Mautic\PluginBundle\Helper\IntegrationHelper $integrationHelper;

    public function __construct(AssetsHelper $assetsHelper, IntegrationHelper $integrationHelper)
    {
        $this->assetsHelper      = $assetsHelper;
        $this->integrationHelper = $integrationHelper;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            PageEvents::PAGE_ON_DISPLAY => ['onPageDisplay', 0],
        ];
    }

    public function onPageDisplay(PageDisplayEvent $event): void
    {
        $integrationObject = $this->integrationHelper->getIntegrationObject('OneSignal');
        $settings          = $integrationObject->getIntegrationSettings();
        $features          = $settings->getFeatureSettings();

        $script = '';
        if (!in_array('landing_page_enabled', $features)) {
            $script = 'disable_notification = true;';
        }

        $this->assetsHelper->addScriptDeclaration($script, 'onPageDisplay_headClose');
    }
}
