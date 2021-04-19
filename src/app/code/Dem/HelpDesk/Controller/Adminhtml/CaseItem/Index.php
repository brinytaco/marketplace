<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml\CaseItem;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;

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
class Index extends \Magento\Backend\App\Action
{
    /**
     * @var type \Dem\HelpDesk\Helper\Data
     */
    protected $helper;

    /**
     * @param Context $context
     * @param \Dem\HelpDesk\Helper\Data $helper
     * @return void
     */
    public function __construct(
            Context $context,
            \Dem\HelpDesk\Helper\Data $helper
    ) {
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $frontendLabel = $this->helper->getConfiguredFrontendLabel();
        $label = sprintf('%s %s', $frontendLabel, __('Cases'));
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend($label);
        return $resultPage;
    }
}
