<?php

declare(strict_types=1);

namespace Mautic\IntegrationsBundle\Sync\DAO\Sync\Report;

use Mautic\IntegrationsBundle\Sync\DAO\Value\NormalizedValueDAO;

class FieldDAO
{
    public const FIELD_CHANGED   = 'changed';
    public const FIELD_REQUIRED  = 'required';
    public const FIELD_UNCHANGED = 'unchanged';

    private string $name;

    private \Mautic\IntegrationsBundle\Sync\DAO\Value\NormalizedValueDAO $value;

    /**
     * @var \DateTimeInterface|null
     */
    private $changeDateTime;

    private string $state;

    public function __construct(string $name, NormalizedValueDAO $value, string $state = self::FIELD_CHANGED)
    {
        $this->name  = $name;
        $this->value = $value;
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getValue(): NormalizedValueDAO
    {
        return $this->value;
    }

    public function getChangeDateTime(): ?\DateTimeInterface
    {
        return $this->changeDateTime;
    }

    public function setChangeDateTime(\DateTimeInterface $changeDateTime): self
    {
        $this->changeDateTime = $changeDateTime;

        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }
}
