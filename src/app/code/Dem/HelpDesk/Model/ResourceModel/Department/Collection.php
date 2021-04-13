<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel\Department;

/**
 * HelpDesk Resource Model - Case Collection
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Identifier field name for collection items
     *
     * @var string
     */
    protected $_idFieldName = 'department_id';

    /**
     * Name prefix of events that are dispatched by model
     *
     * @var string
     */
    protected $_eventPrefix = 'dem_helpdesk_department_collection';

    /**
     * Name of event parameter
     *
     * @var string
     */
    protected $_eventObject = 'department_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Dem\HelpDesk\Model\Department::class,
            \Dem\HelpDesk\Model\ResourceModel\Department::class
        );
    }
}
