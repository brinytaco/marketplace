<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml;

/*
 * Adminhtml Caseitem Grid Container
 *
 * @author      Toby Crain <tcrain@directedgemedia.com>
 * @copyright © Direct Edge Media, Inc. All rights reserved.
 */
class Caseitem extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_caseitem';
        $this->_blockGroup = 'Dem_HelpDesk';
        $this->_headerText = __('Manage Cases');
        $this->_addButtonLabel = __('Create New Case');
        parent::_construct();
    }
}