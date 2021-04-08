<?php

namespace Dem\HelpDesk\Block\Adminhtml;

/*
 * Adminhtml Topic Grid Container
 *
 * @author      Toby Crain <tcrain@directedgemedia.com>
 * @copyright © Direct Edge Media, Inc. All rights reserved.
 */
class Topic extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_topic';
        $this->_blockGroup = 'Dem_HelpDesk';
        $this->_headerText = __('Manage Topics');
        $this->_addButtonLabel = __('Create New Topic');
        parent::_construct();
    }
}