<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel\Reply;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\ResourceModel\Reply as Resource;

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
            Reply::class,
            Resource::class
        );
    }

    /**
     * Add extra fields as output columns
     * author_name
     *
     * @return $this
     * @since 1.0.0
     */
    protected function _initSelect()
    {
        parent::_initSelect();

        // Add helpdesk user name to select
        $this->getSelect()->joinLeft(
            ['u' => $this->getTable('dem_helpdesk_user')],
            'main_table.author_id = u.user_id',
            ['author_name' => 'u.name']
        );

        return $this;
    }
}
