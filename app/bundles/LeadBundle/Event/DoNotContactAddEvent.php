<?php

declare(strict_types=1);

namespace Mautic\LeadBundle\Event;

use Mautic\LeadBundle\Entity\DoNotContact as DNC;
use Mautic\LeadBundle\Entity\Lead;
use Symfony\Contracts\EventDispatcher\Event;

final class DoNotContactAddEvent extends Event
{
    public const ADD_DONOT_CONTACT = 'mautic.lead.add_donot_contact';

    private \Mautic\LeadBundle\Entity\Lead $lead;

    private string $channel;

    private string $comments;

    private int $reason;

    private bool $persist;

    private bool $checkCurrentStatus;

    private bool $override;

    public function __construct(Lead $lead, string $channel, string $comments = '', int $reason = DNC::BOUNCED, bool $persist = true, bool $checkCurrentStatus = true, bool $override = true)
    {
        $this->lead               = $lead;
        $this->channel            = $channel;
        $this->comments           = $comments;
        $this->reason             = $reason;
        $this->persist            = $persist;
        $this->checkCurrentStatus = $checkCurrentStatus;
        $this->override           = $override;
    }

    public function getLead(): Lead
    {
        return $this->lead;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function getComments(): string
    {
        return $this->comments;
    }

    public function getReason(): int
    {
        return $this->reason;
    }

    public function isPersist(): bool
    {
        return $this->persist;
    }

    public function isCheckCurrentStatus(): bool
    {
        return $this->checkCurrentStatus;
    }

    public function isOverride(): bool
    {
        return $this->override;
    }
}
