<?php

namespace Mautic\FormBundle\EventListener;

use Mautic\FormBundle\Event\SubmissionEvent;
use Mautic\FormBundle\FormEvents;
use Mautic\WebhookBundle\Event\WebhookBuilderEvent;
use Mautic\WebhookBundle\Model\WebhookModel;
use Mautic\WebhookBundle\WebhookEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WebhookSubscriber implements EventSubscriberInterface
{
    private \Mautic\WebhookBundle\Model\WebhookModel $webhookModel;

    public function __construct(WebhookModel $webhookModel)
    {
        $this->webhookModel = $webhookModel;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            WebhookEvents::WEBHOOK_ON_BUILD => ['onWebhookBuild', 0],
            FormEvents::FORM_ON_SUBMIT      => ['onFormSubmit', 0],
        ];
    }

    /**
     * Add event triggers and actions.
     */
    public function onWebhookBuild(WebhookBuilderEvent $event): void
    {
        // add checkbox to the webhook form for new leads
        $formSubmit = [
            'label'       => 'mautic.form.webhook.event.form.submit',
            'description' => 'mautic.form.webhook.event.form.submit_desc',
        ];

        // add it to the list
        $event->addEvent(FormEvents::FORM_ON_SUBMIT, $formSubmit);
    }

    public function onFormSubmit(SubmissionEvent $event): void
    {
        $this->webhookModel->queueWebhooksByType(
            FormEvents::FORM_ON_SUBMIT,
            [
                'submission' => $event->getSubmission(),
            ],
            [
                'submissionDetails',
                'ipAddress',
                'leadList',
                'pageList',
                'formList',
            ]
        );
    }
}
