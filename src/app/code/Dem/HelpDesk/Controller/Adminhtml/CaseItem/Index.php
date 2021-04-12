<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml\Caseitem;

use Magento\Framework\Controller\ResultFactory;

/*
 * Adminhtml Caseitem grid action
 *
 * @author      Toby Crain <tcrain@directedgemedia.com>
 * @copyright © Direct Edge Media, Inc. All rights reserved.
 */
class Index extends \Magento\Backend\App\Action
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Cases'));
        return $resultPage;
    }
}
