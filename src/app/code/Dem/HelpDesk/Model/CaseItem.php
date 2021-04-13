<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

/**
 * HelpDesk Model - Case
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Caseitem extends \Magento\Framework\Model\AbstractModel
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
        $this->_init(\Dem\HelpDesk\Model\ResourceModel\Caseitem::class);
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
