<?php

declare(strict_types=1);

namespace Mautic\LeadBundle\Twig\Extension;

use Mautic\LeadBundle\Exception\UnknownDncReasonException;
use Mautic\LeadBundle\Twig\Helper\DncReasonHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DncReasonExtension extends AbstractExtension
{
    protected \Mautic\LeadBundle\Twig\Helper\DncReasonHelper $helper;

    public function __construct(DncReasonHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('dncReasonToText', [$this, 'toText']),
        ];
    }

    /**
     * Convert DNC reason ID to text.
     *
     * @throws UnknownDncReasonException
     */
    public function toText(int $reasonId): string
    {
        return $this->helper->toText($reasonId);
    }
}
