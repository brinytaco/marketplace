<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\App\ObjectManager;

/**
 * HelpDesk Model - Department
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class Department extends \Magento\Framework\Model\AbstractModel
{
    const CURRENT_KEY = 'current_department';

    /**
     * @var string
     */
    protected $_eventPrefix = 'helpdesk_department';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Dem\HelpDesk\Model\ResourceModel\Department::class);
    }

    /**
     * After load, set display names
     *
     * @return \Dem\HelpDesk\Model\Department
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();
        return $this;
    }


}
