<?php

namespace Dem\HelpDesk\Model;

/**
 * CaseItem Model
 *
 * @author      Toby Crain <tcrain@directedgemedia.com>
 * @copyright © Direct Edge Media, Inc. All rights reserved.
 */
class CaseItem extends \Magento\Framework\Model\AbstractModel implements
        \Magento\Framework\DataObject\IdentityInterface
{
    const CURRENT_KEY = 'current_case';

    /**
     * @var string|array|bool
     */
    protected $_cacheTag = 'dem_helpdesk_case';

    /**
     * @var string
     */
    protected $_eventPrefix = 'dem_helpdesk_case';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Dem\HelpDesk\Model\ResourceModel\CaseItem::class);
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
