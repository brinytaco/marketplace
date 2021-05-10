<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel\DepartmentUser;

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
    const EVENT_PREFIX = 'helpdesk_department_user_collection';

    /**
     * Identifier field name for collection items
     *
     * @var string
     */
    protected $_idFieldName = 'dept_user_id';

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
    protected $_eventObject = 'helpdesk_department_user_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Dem\HelpDesk\Model\DepartmentUser::class,
            \Dem\HelpDesk\Model\ResourceModel\DepartmentUser::class
        );
    }
}