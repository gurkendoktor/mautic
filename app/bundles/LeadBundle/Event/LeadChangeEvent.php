<?php

namespace Mautic\LeadBundle\Event;

use Mautic\LeadBundle\Entity\Lead;
use Symfony\Contracts\EventDispatcher\Event;

class LeadChangeEvent extends Event
{
    private \Mautic\LeadBundle\Entity\Lead $oldLead;

    private $oldTrackingId;

    private \Mautic\LeadBundle\Entity\Lead $newLead;

    private $newTrackingId;

    public function __construct(Lead $oldLead, $oldTrackingId, Lead $newLead, $newTrackingId)
    {
        $this->oldLead       = $oldLead;
        $this->oldTrackingId = $oldTrackingId;
        $this->newLead       = $newLead;
        $this->newTrackingId = $newTrackingId;
    }

    /**
     * @return Lead
     */
    public function getOldLead()
    {
        return $this->oldLead;
    }

    /**
     * @return mixed
     */
    public function getOldTrackingId()
    {
        return $this->oldTrackingId;
    }

    /**
     * @return Lead
     */
    public function getNewLead()
    {
        return $this->newLead;
    }

    /**
     * @return mixed
     */
    public function getNewTrackingId()
    {
        return $this->newTrackingId;
    }
}
