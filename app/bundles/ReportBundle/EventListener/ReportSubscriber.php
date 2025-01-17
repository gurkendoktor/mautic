<?php

namespace Mautic\ReportBundle\EventListener;

use Mautic\CoreBundle\Helper\IpLookupHelper;
use Mautic\CoreBundle\Model\AuditLogModel;
use Mautic\ReportBundle\Event\ReportEvent;
use Mautic\ReportBundle\ReportEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ReportSubscriber implements EventSubscriberInterface
{
    private \Mautic\CoreBundle\Helper\IpLookupHelper $ipLookupHelper;

    private \Mautic\CoreBundle\Model\AuditLogModel $auditLogModel;

    public function __construct(IpLookupHelper $ipLookupHelper, AuditLogModel $auditLogModel)
    {
        $this->ipLookupHelper = $ipLookupHelper;
        $this->auditLogModel  = $auditLogModel;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ReportEvents::REPORT_POST_SAVE   => ['onReportPostSave', 0],
            ReportEvents::REPORT_POST_DELETE => ['onReportDelete', 0],
        ];
    }

    /**
     * Add an entry to the audit log.
     */
    public function onReportPostSave(ReportEvent $event): void
    {
        $report = $event->getReport();
        if ($details = $event->getChanges()) {
            $log = [
                'bundle'    => 'report',
                'object'    => 'report',
                'objectId'  => $report->getId(),
                'action'    => ($event->isNew()) ? 'create' : 'update',
                'details'   => $details,
                'ipAddress' => $this->ipLookupHelper->getIpAddressFromRequest(),
            ];
            $this->auditLogModel->writeToLog($log);
        }
    }

    /**
     * Add a delete entry to the audit log.
     */
    public function onReportDelete(ReportEvent $event): void
    {
        $report = $event->getReport();
        $log    = [
            'bundle'    => 'report',
            'object'    => 'report',
            'objectId'  => $report->deletedId,
            'action'    => 'delete',
            'details'   => ['name' => $report->getName()],
            'ipAddress' => $this->ipLookupHelper->getIpAddressFromRequest(),
        ];
        $this->auditLogModel->writeToLog($log);
    }
}
