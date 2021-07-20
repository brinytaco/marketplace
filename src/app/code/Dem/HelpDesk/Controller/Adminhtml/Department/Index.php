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
class Index extends Controller
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->initAction();
        $this->getPageTitle()->prepend(__('Manage Departments'));
        return $resultPage;
    }
}
