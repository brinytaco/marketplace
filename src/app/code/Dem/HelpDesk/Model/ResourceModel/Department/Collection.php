<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel\Department;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

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
class Collection extends AbstractCollection
{
    const EVENT_PREFIX = 'helpdesk_department_collection';

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
    protected $_eventPrefix = self::EVENT_PREFIX;

    /**
     * Name of event parameter
     *
     * @var string
     */
    protected $_eventObject = 'helpdesk_department_collection';

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

    /**
     * Add extra fields as output columns
     * department_name
     * case_manager_name
     *
     * @return $this
     * @since 1.0.0
     */
    protected function _initSelect()
    {
        parent::_initSelect();

        // Add case_manager name to select
        $this->getSelect()->join(
            ['u' => $this->getTable('dem_helpdesk_user')],
            'main_table.case_manager_id = u.user_id',
            ['case_manager_name' => 'u.name']
        );

        return $this;
    }
}
