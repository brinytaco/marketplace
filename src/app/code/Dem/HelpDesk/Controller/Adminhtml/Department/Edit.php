<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml\Department;

use Dem\HelpDesk\Controller\Adminhtml\Department;
use Magento\Backend\Model\View\Result\Page;

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
class Edit extends Department
{
    /**
     * @return Page
     */
    public function execute()
    {
        /** @var \Dem\HelpDesk\Model\Department $department */
        $department = $this->initDepartment();

        if ($department) {
            /** @var Page $resultPage */
            $resultPage = $this->initAction();
            $resultPage->getConfig()->getTitle()->prepend(sprintf(
                '%s #%s',
                __('Editing'),
                $department->getName()
            ));
            return $resultPage;
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->redirectFactory->create();
        $resultRedirect->setPath('helpdesk/department');
        $this->messageManager->addErrorMessage(__('This %1 no longer exists.', __('department')));
        return $resultRedirect;
    }
}
