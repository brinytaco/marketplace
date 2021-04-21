<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;

/**
 * HelpDesk - Adminhtml Abstract Controller
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
abstract class CaseItem extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ACTION_RESOURCE = 'Dem_HelpDesk::entity';

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Result Page Factory
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Result Forward Factory
     *
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var type \Dem\HelpDesk\Helper\Data
     */
    protected $helper;

    /**
     * Data constructor.
     *
     * @param Registry $registry
     * @param PageFactory $resultPageFactory
     * @param ForwardFactory $resultForwardFactory
     * @param Context $context
     * @param \Dem\HelpDesk\Helper\Data $helper
     */
    public function __construct(
        Registry $registry,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Context $context,
        \Dem\HelpDesk\Helper\Data $helper
    ) {
        $this->coreRegistry         = $registry;
        $this->resultPageFactory    = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->helper = $helper;
        parent::__construct($context);
    }
}
