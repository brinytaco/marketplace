<?php

namespace Dem\HelpDesk\Block\Adminhtml;

/*
 * Adminhtml CaseItem Grid Container
 *
 * @author      Toby Crain <tcrain@directedgemedia.com>
 * @copyright © Direct Edge Media, Inc. All rights reserved.
 */
class CaseItem extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_case_item';
        $this->_blockGroup = 'Dem_HelpDesk';
        $this->_headerText = __('Manage Cases');
        $this->_addButtonLabel = __('Create New Case');
        parent::_construct();
    }
}