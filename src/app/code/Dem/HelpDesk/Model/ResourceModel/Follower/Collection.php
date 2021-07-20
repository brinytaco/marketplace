<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel\Follower;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * HelpDesk Resource Model - Follower Collection
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
    const EVENT_PREFIX = 'helpdesk_follower_collection';

    /**
     * Identifier field name for collection items
     *
     * @var string
     */
    protected $_idFieldName = 'follower_id';

    /**
     * @var string
     */
    protected $_eventPrefix = self::EVENT_PREFIX;

    /**
     * Name of event parameter
     *
     * @var string
     */
    protected $_eventObject = 'helpdesk_follower_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Dem\HelpDesk\Model\Follower::class,
            \Dem\HelpDesk\Model\ResourceModel\Follower::class
        );
    }

}