<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml\Department;

use Dem\HelpDesk\Controller\Adminhtml\Department;

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
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $objectStr = __('department');

        try {
            $department = $this->_initDepartment();
            $resultPage = $this->_initAction();
            $resultPage->getConfig()->getTitle()->prepend(sprintf('%s `%s`',
                __('Editing'),
                $department->getName(),
            ));
            return $resultPage;
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(__('Exception occurred during %1 load', $objectStr));
        }

        $resultRedirect->setPath('helpdesk/department/');
        return $resultRedirect;
    }
}
