<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel\Follower;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Dem\HelpDesk\Model\Follower;
use Dem\HelpDesk\Model\ResourceModel\Follower as Resource;

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
    /**
     * Identifier field name for collection items
     *
     * @var string
     */
    protected $_idFieldName = 'follower_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'helpdesk_follower_collection';

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
            Follower::class,
            Resource::class
        );
    }

}
