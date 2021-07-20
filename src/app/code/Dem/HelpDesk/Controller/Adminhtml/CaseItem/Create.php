<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml\CaseItem;

use Dem\HelpDesk\Controller\Adminhtml\CaseItem;
use Magento\Backend\Model\View\Result\Page;

/**
 * HelpDesk Controller - Adminhtml Case View
 *
 * Uses layout definition from:
 * view/adminhtml/layout/dem_helpdesk_caseitem_create.xml
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Create extends CaseItem
{
    /**
     * @return Page
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->initAction();
        $resultPage->getConfig()->getTitle()->prepend(__('Open a New Case'));
        return $resultPage;
    }
}
