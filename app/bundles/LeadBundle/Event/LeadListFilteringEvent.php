<?php

namespace Mautic\LeadBundle\Event;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Mautic\CoreBundle\Event\CommonEvent;
use Mautic\LeadBundle\Segment\Query\QueryBuilder;

/**
 * Please refer to LeadListRepository.php, inside getListFilterExprCombined method, for examples.
 */
class LeadListFilteringEvent extends CommonEvent
{
    /**
     * @var array
     */
    protected $details;

    /**
     * @var int
     */
    protected $leadId;

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    protected bool $isFilteringDone;

    /**
     * @var string
     */
    protected $alias;

    protected string $subQuery;

    /**
     * @var string
     */
    protected $func;

    private string $leadsTableAlias;

    /**
     * @param array        $details
     * @param int          $leadId
     * @param string       $alias
     * @param string       $func
     * @param QueryBuilder $queryBuilder
     */
    public function __construct($details, $leadId, $alias, $func, $queryBuilder, EntityManager $entityManager)
    {
        $this->details         = $details;
        $this->leadId          = $leadId;
        $this->alias           = $alias;
        $this->func            = $func;
        $this->queryBuilder    = $queryBuilder;
        $this->em              = $entityManager;
        $this->isFilteringDone = false;
        $this->subQuery        = '';
        $this->leadsTableAlias = $queryBuilder->getTableAlias(MAUTIC_TABLE_PREFIX.'leads');
    }

    /**
     * @return array
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @return int
     */
    public function getLeadId()
    {
        return $this->leadId;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getFunc()
    {
        return $this->func;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    /**
     * @param bool $status
     */
    public function setFilteringStatus($status): void
    {
        $this->isFilteringDone = $status;
    }

    /**
     * @param string $query
     */
    public function setSubQuery($query): void
    {
        $this->subQuery = $query;

        $this->setFilteringStatus(true);
    }

    /**
     * @return bool
     */
    public function isFilteringDone()
    {
        return $this->isFilteringDone;
    }

    /**
     * @return string
     */
    public function getSubQuery()
    {
        return $this->subQuery;
    }

    /**
     * @param array $details
     */
    public function setDetails($details): void
    {
        $this->details = $details;
    }

    public function getLeadsTableAlias(): string
    {
        return $this->leadsTableAlias;
    }
}
