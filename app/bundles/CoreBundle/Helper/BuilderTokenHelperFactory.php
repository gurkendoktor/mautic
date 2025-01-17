<?php

namespace Mautic\CoreBundle\Helper;

use Doctrine\DBAL\Connection;
use Mautic\CoreBundle\Factory\ModelFactory;
use Mautic\CoreBundle\Security\Permissions\CorePermissions;

class BuilderTokenHelperFactory
{
    private \Mautic\CoreBundle\Security\Permissions\CorePermissions $security;

    /**
     * @var ModelFactory<object>
     */
    private \Mautic\CoreBundle\Factory\ModelFactory $modelFactory;

    private \Doctrine\DBAL\Connection $connection;

    private \Mautic\CoreBundle\Helper\UserHelper $userHelper;

    /**
     * @param ModelFactory<object> $modelFactory
     */
    public function __construct(
        CorePermissions $security,
        ModelFactory $modelFactory,
        Connection $connection,
        UserHelper $userHelper
    ) {
        $this->security      = $security;
        $this->modelFactory  = $modelFactory;
        $this->connection    = $connection;
        $this->userHelper    = $userHelper;
    }

    public function getBuilderTokenHelper(
        string $modelName,
        ?string $viewPermissionBase = null,
        ?string $bundleName = null,
        ?string $langVar = null
    ): BuilderTokenHelper {
        $builderTokenHelper = new BuilderTokenHelper($this->security, $this->modelFactory, $this->connection, $this->userHelper);
        $builderTokenHelper->configure($modelName, $viewPermissionBase, $bundleName, $langVar);

        return $builderTokenHelper;
    }
}
