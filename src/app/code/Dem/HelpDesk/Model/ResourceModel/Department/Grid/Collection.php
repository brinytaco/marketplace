<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel\Department\Grid;

use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Document;
use Dem\HelpDesk\Model\ResourceModel\Department\Collection as DepartmentCollection;
use Dem\HelpDesk\Model\ResourceModel\Department;

/**
 * HelpDesk Resource Model - Department Grid Collection
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Collection extends DepartmentCollection implements SearchResultInterface
{
    /**
     * @var \Magento\Framework\Api\Search\AggregationInterface
     */
    protected $aggregations;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaInterface
     */
    protected $searchCriteria;

    /**
     * @var int
     */
    protected $totalCount;

    /**
     * @var \Magento\Framework\Api\ExtensibleDataInterface[]
     */
    protected $items;

    /**
     * Name prefix of events that are dispatched by model
     *
     * @var string
     */
    protected $_eventPrefix = 'helpdesk_department_grid_collection';

    /**
     * Name of event parameter
     *
     * @var string
     */
    protected $_eventObject = 'helpdesk_department_grid_collection';


    /**
     * Define resource model
     *
     * @return void
     * @codeCoverageIgnore
     */
    public function _construct()
    {
        $this->_init(
            Document::class,
            Department::class
        );

        // Add department_name alias to grid filter
        $this->addFilterToMap('department_name', 'd.name');
        $this->addFilterToMap('website_id', 'main_table.website_id');
        $this->addFilterToMap('case_manager_name', 'u.name');
    }


    /**
     * @return \Magento\Framework\Api\Search\AggregationInterface
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * @param AggregationInterface $aggregations
     * @return $this
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
        return $this;
    }

    /**
     * Get search criteria.
     *
     * @return \Magento\Framework\Api\SearchCriteriaInterface|null
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getSearchCriteria()
    {
        return $this->searchCriteria;
    }

    /**
     * Set search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return $this
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function setSearchCriteria(SearchCriteriaInterface $searchCriteria = null)
    {
        $this->searchCriteria = $searchCriteria;
        return $this;
    }

    /**
     * Get total count.
     *
     * @return int
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getTotalCount()
    {
        if (!isset($this->totalCount)) {
            $this->totalCount = $this->getSize();
        }
        return $this->totalCount;
    }

    /**
     * Set total count.
     *
     * @param int $totalCount
     * @return $this
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;
        return $this;
    }

    /**
     * Set items list.
     *
     * @param ExtensibleDataInterface[] $items
     * @return $this
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function setItems(array $items = null)
    {
        $this->items = $items;
        return $this;
    }
}
