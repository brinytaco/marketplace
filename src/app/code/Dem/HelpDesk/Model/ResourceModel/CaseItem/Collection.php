<?php

namespace Dem\HelpDesk\Model\ResourceModel\CaseItem;

/**
 * CaseItem Resource Collection
 *
 * @author      Toby Crain <tcrain@directedgemedia.com>
 * @copyright © Direct Edge Media, Inc. All rights reserved.
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Identifier field name for collection items
     *
     * @var string
     */
    protected $_idFieldName = 'case_id';

    /**
     * Name prefix of events that are dispatched by model
     *
     * @var string
     */
    protected $_eventPrefix = 'dem_helpdesk_case_collection';

    /**
     * Name of event parameter
     *
     * @var string
     */
    protected $_eventObject = 'case_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            Dem\HelpDesk\Model\CaseItem::class,
            Dem\HelpDesk\Model\ResourceModel\CaseItem::class
        );
    }
}
