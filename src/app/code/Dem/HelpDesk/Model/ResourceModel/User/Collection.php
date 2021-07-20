<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\ResourceModel\User;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Dem\HelpDesk\Model\User;
use Dem\HelpDesk\Model\ResourceModel\User as Resource;

/**
 * HelpDesk Resource Model - User Collection
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
    protected $_idFieldName = 'user_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'helpdesk_user_collection';

    /**
     * Name of event parameter
     *
     * @var string
     */
    protected $_eventObject = 'helpdesk_user_collection';

    /**
     * Define resource model
     *
     * @return void
     * @codeCoverageIgnore
     */
    protected function _construct()
    {
        $this->_init(
            User::class,
            Resource::class
        );
    }

}
