<?php

namespace Mautic\EmailBundle\Event;

use Symfony\Component\Form\FormBuilder;
use Symfony\Contracts\EventDispatcher\Event;

class MonitoredEmailEvent extends Event
{
    private \Symfony\Component\Form\FormBuilder $formBuilder;

    private array $data;

    /**
     * @var array
     */
    private $folders = [];

    public function __construct(FormBuilder $builder, array $data)
    {
        $this->formBuilder = $builder;
        $this->data        = $data;
    }

    /**
     * Get the FormBuilder for monitored_mailboxes FormType.
     *
     * @return FormBuilder
     */
    public function getFormBuilder()
    {
        return $this->formBuilder;
    }

    /**
     * Insert a folder to configure.
     *
     * @param string $default
     */
    public function addFolder($bundleKey, $folderKey, $label, $default = ''): void
    {
        $keyName = ($folderKey) ? $bundleKey.'_'.$folderKey : $bundleKey;

        $this->folders[$keyName] = [
            'label'   => $label,
            'default' => $default,
        ];
    }

    /**
     * Get the value set for a specific bundle/folder.
     *
     * @return string
     */
    public function getData($bundleKey, $folderKey, $default = '')
    {
        $keyName = $bundleKey.'_'.$folderKey;

        return (isset($this->data[$keyName])) ? $this->data[$keyName] : $default;
    }

    /**
     * Get array of folders.
     *
     * @return array
     */
    public function getFolders()
    {
        return $this->folders;
    }
}
