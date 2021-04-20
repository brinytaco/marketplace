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
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @param Context $context
     * @param \Dem\HelpDesk\Helper\Data $helper
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     * @return void
     */
    public function __construct(
            Context $context,
            \Dem\HelpDesk\Helper\Data $helper,
            \Magento\Framework\View\LayoutFactory $layoutFactory
    ) {
        $this->helper = $helper;
        $this->layoutFactory = $layoutFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        if ($this->getRequest()->isAjax()) {
            $output = $this->layoutFactory->create()
                ->createBlock(\Dem\HelpDesk\Block\Adminhtml\CaseItem\Grid::class)
                ->toHtml();


            $this->getResponse()->appendBody($output);
        }

        $frontendLabel = $this->helper->getConfiguredFrontendLabel();
        $label = sprintf('%s %s', $frontendLabel, __('Cases'));
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend($label);
        return $resultPage;
    }
}
