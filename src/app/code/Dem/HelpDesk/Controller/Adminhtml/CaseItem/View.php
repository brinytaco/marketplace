<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml\CaseItem;

use Dem\HelpDesk\Controller\Adminhtml\CaseItem as Controller;

/**
 * HelpDesk Controller - Adminhtml Case View
 *
 * Uses layout definition from:
 * view/adminhtml/layout/dem_helpdesk_caseitem_view.xml
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class View extends Controller
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $case = $this->initCase();

        if ($case) {
            $resultPage = $this->initAction();
            $this->getPageTitle()->prepend(sprintf(
                '%s #%s',
                __('Case'),
                $case->getCaseNumber()
            ));
            return $resultPage;
        }

        $resultRedirect = $this->getRedirect();
        $resultRedirect->setPath('helpdesk/caseitem');
        $this->getMessageManager()->addErrorMessage(__('This %1 no longer exists.', __('case')));
        return $resultRedirect;
    }
}
