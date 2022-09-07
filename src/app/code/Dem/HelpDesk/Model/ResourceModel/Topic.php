<?php

namespace Dem\HelpDesk\Model\ResourceModel;

/**
 * Topic Resource
 *
 * @author      Toby Crain <tcrain@directedgemedia.com>
 * @copyright © Direct Edge Media, Inc. All rights reserved.
 */
class Topic extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Instantiation
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @return void
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('dem_helpdesk_topic', 'topic_id');
    }
}
