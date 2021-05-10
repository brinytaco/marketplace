<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel\Reply;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * HelpDesk Resource Model - Reply Collection
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
    const EVENT_PREFIX = 'helpdesk_reply_collection';

    /**
     * Identifier field name for collection items
     *
     * @var string
     */
    protected $_idFieldName = 'reply_id';

    /**
     * @var string
     */
    protected $_eventPrefix = self::EVENT_PREFIX;

    /**
     * Name of event parameter
     *
     * @var string
     */
    protected $_eventObject = 'helpdesk_reply_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Dem\HelpDesk\Model\Reply::class,
            \Dem\HelpDesk\Model\ResourceModel\Reply::class
        );
    }

    /**
     * Add extra fields as output columns
     * department_name
     * case_manager_name
     *
     */
    protected function _initSelect()
    {

        parent::_initSelect();

        // Add department name to select
        $this->getSelect()->join(
            ['d' => $this->getTable('dem_helpdesk_department')],
            'd.department_id = main_table.department_id',
            ['department_name' => 'name']
        );

        return $this;
    }

}