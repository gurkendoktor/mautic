<?php

declare(strict_types=1);

namespace Mautic\LeadBundle\Field\Dispatcher;

use Mautic\LeadBundle\Entity\LeadField;
use Mautic\LeadBundle\Field\Event\AddColumnEvent;
use Mautic\LeadBundle\Field\Exception\AbortColumnCreateException;
use Mautic\LeadBundle\Field\Settings\BackgroundSettings;
use Mautic\LeadBundle\LeadEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FieldColumnDispatcher
{
    private \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher;

    private \Mautic\LeadBundle\Field\Settings\BackgroundSettings $backgroundSettings;

    public function __construct(EventDispatcherInterface $dispatcher, BackgroundSettings $backgroundSettings)
    {
        $this->dispatcher         = $dispatcher;
        $this->backgroundSettings = $backgroundSettings;
    }

    /**
     * @throws AbortColumnCreateException
     */
    public function dispatchPreAddColumnEvent(LeadField $leadField): void
    {
        $shouldProcessInBackground = $this->backgroundSettings->shouldProcessColumnChangeInBackground();
        $event                     = new AddColumnEvent($leadField, $shouldProcessInBackground);

        $this->dispatcher->dispatch($event, LeadEvents::LEAD_FIELD_PRE_ADD_COLUMN);

        if ($shouldProcessInBackground) {
            throw new AbortColumnCreateException('Column change will be processed in background job');
        }
    }
}
