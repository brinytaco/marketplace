<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml\CaseItem;

use Dem\HelpDesk\Controller\Adminhtml\CaseItem;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;

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
class View extends CaseItem
{
    /**
     * @return Page|Redirect
     */
    public function execute()
    {
        /** @var \Dem\HelpDesk\Model\CaseItem $case */
        $case = $this->initCase();

        if ($case) {
            /** @var Page $resultPage */
            $resultPage = $this->initAction();
            $resultPage->getConfig()->getTitle()->prepend(sprintf(
                '%s #%s',
                __('Case'),
                $case->getCaseNumber()
            ));
            return $resultPage;
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->redirectFactory->create();
        $resultRedirect->setPath('helpdesk/caseitem');
        $this->messageManager->addErrorMessage(__('This %1 no longer exists.', __('case')));
        return $resultRedirect;
    }
}
