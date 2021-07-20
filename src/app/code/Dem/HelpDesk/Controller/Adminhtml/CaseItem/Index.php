<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml\CaseItem;

use Dem\HelpDesk\Controller\Adminhtml\CaseItem as Controller;

/**
 * HelpDesk Controller - Adminhtml Case Grid (Index)
 *
 * Uses layout definition from:
 * view/adminhtml/layout/dem_helpdesk_caseitem_index.xml
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
        $this->getPageTitle()->prepend(__('Manage Cases'));
        return $resultPage;
    }
}
