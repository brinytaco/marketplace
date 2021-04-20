<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml;

/**
 * HelpDesk Block - Adminhtml Case Grid Container
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class CaseItem extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_case';
        $this->_blockGroup = 'Dem_HelpDesk';
        $this->_headerText = __('Manage Cases');
        $this->_addButtonLabel = __('Open New Case');
        parent::_construct();
    }

}
