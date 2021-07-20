<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel\Department;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Dem\HelpDesk\Model\Department;
use Dem\HelpDesk\Model\ResourceModel\Department as Resource;

/**
 * HelpDesk Resource Model - Department Collection
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
    protected $_eventPrefix = 'helpdesk_department_collection';

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
     * @codeCoverageIgnore
     */
    protected function _construct()
    {
        $this->_init(
            Department::class,
            Resource::class
        );
    }

    /**
     * Add extra fields as output columns
     * department_name
     * case_manager_name
     *
     * @return $this
     * @since 1.0.0
     * @codeCoverageIgnore
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
