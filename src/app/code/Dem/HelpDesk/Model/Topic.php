<?php

namespace Dem\HelpDesk\Model;

/**
 * Topic Model
 *
 * @author      Toby Crain <tcrain@directedgemedia.com>
 * @copyright © Direct Edge Media, Inc. All rights reserved.
 */
class Topic extends \Magento\Framework\Model\AbstractModel implements
    \Magento\Framework\DataObject\IdentityInterface
{
    const CURRENT_KEY = 'current_topic';

    /**
     * @var string|array|bool
     */
    protected $_cacheTag = 'dem_helpdesk_topic';

    /**
     * @var string
     */
    protected $_eventPrefix = 'dem_helpdesk_topic';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Dem\HelpDesk\Model\ResourceModel\Topic::class);
    }

    /**
     * @return string[]
     */
    public function getIdentities()
    {
        return [$this->_cacheTag . '_' . $this->getId()];
    }

    /**
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
