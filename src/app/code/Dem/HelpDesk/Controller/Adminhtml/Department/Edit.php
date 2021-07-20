<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml\Department;

use Dem\HelpDesk\Controller\Adminhtml\Department as Controller;

/**
 * HelpDesk Controller - Adminhtml Department Edit
 *
 * Uses layout definition from:
 * view/adminhtml/layout/dem_helpdesk_department_edit.xml
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Edit extends Controller
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $department = $this->initDepartment();

        if ($department) {
            $resultPage = $this->initAction();
            $this->getPageTitle()->prepend(sprintf(
                '%s: %s',
                __('Editing'),
                $department->getName()
            ));
            return $resultPage;
        }

        $resultRedirect = $this->getRedirect();
        $resultRedirect->setPath('helpdesk/department');
        $this->getMessageManager()->addErrorMessage(__('This %1 no longer exists.', __('department')));
        return $resultRedirect;
    }
}
