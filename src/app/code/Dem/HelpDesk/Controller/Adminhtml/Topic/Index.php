<?php

namespace Dem\HelpDesk\Controller\Adminhtml\Topic;

use Magento\Framework\Controller\ResultFactory;

/*
 * Adminhtml Topic grid action
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
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Topics'));
        return $resultPage;
    }
}
