<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml\Department;

use Dem\HelpDesk\Controller\Adminhtml\Department as Controller;

/**
 * HelpDesk Controller - Adminhtml Department Grid (Index)
 *
 * Uses layout definition from:
 * view/adminhtml/layout/dem_helpdesk_department_index.xml
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Cases extends Controller
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->getResultPage();
        // $resultPage = $this->initAction();
        // return $resultPage;

        // Not rendering page like a regular action
        // We're just rendering the target layout only
        $this->_view->loadLayout();
        $this->_view->renderLayout();
        // return $this->_view;
    }
}
