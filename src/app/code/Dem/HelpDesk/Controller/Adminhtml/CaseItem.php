<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml;

use Dem\HelpDesk\Model\CaseItem as CaseModel;
use Dem\Base\Controller\Adminhtml\AbstractAction;
use Dem\HelpDesk\Model\CaseItemRepository;
use Dem\HelpDesk\Model\Service\CaseItemManagement;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\ReplyFactory;
use Dem\HelpDesk\Model\Service\ReplyManagement;
use Dem\HelpDesk\Model\FollowerFactory;
use Dem\HelpDesk\Model\Service\FollowerManagement;
use Dem\HelpDesk\Model\Service\Notifications;
use Dem\HelpDesk\Helper\Data as Helper;

use Magento\Backend\App\Action;
use Magento\Framework\App\Request\Http as RequestHttp;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Backend\Model\View\Result\Page;

/**
 * HelpDesk - Adminhtml Abstract Case Controller
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
abstract class CaseItem extends AbstractAction
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ACTION_RESOURCE = 'Dem_HelpDesk::helpdesk_cases';

    /**
     * @var CaseItemRepository
     */
    protected $caseItemRepository;

    /**
     * @var DepartmentRepository
     */
    protected $departmentRepository;

    /**
     * @var CaseItemManagement
     */
    protected $caseItemManager;

    /**
     * @var CaseModel
     */
    protected $caseItem;

    /**
     * @var ReplyManagement
     */
    protected $replyManager;

    /**
     * @var ReplyFactory
     */
    protected $replyFactory;

    /**
     * @var FollowerManagement
     */
    protected $followerManager;

    /**
     * @var FollowerFactory
     */
    protected $followerFactory;

    /**
     * @var Notifications
     */
    protected $notificationService;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * Data constructor.
     *
     * @param Action\Context $context
     * @param Registry $coreRegistry
     * @param LoggerInterface $logger
     * @param RequestHttp $requestHttp
     * @param PageFactory $pageFactory
     * @param JsonFactory $jsonFactory
     * @param LayoutFactory $layoutFactory
     * @param CaseItemRepository $caseItemRepository
     * @param CaseItemManagement $caseItemManager
     * @param ReplyFactory $replyFactory
     * @param ReplyManagement $replyManager
     * @param FollowerFactory $followerFactory
     * @param FollowerManagement $followerManager
     * @param Notifications $notificationService
     * @param Helper $helper
     */
    public function __construct(
        Action\Context $context,
        Registry $coreRegistry,
        LoggerInterface $logger,
        RequestHttp $requestHttp,
        PageFactory $pageFactory,
        JsonFactory $jsonFactory,
        LayoutFactory $layoutFactory,
        CaseItemRepository $caseItemRepository,
        CaseItemManagement $caseItemManager,
        ReplyFactory $replyFactory,
        ReplyManagement $replyManager,
        FollowerFactory $followerFactory,
        FollowerManagement $followerManager,
        Notifications $notificationService,
        Helper $helper
    ) {
        $this->caseItemRepository = $caseItemRepository;
        $this->caseItemManager = $caseItemManager;
        $this->replyFactory = $replyFactory;
        $this->replyManager = $replyManager;
        $this->followerFactory = $followerFactory;
        $this->followerManager = $followerManager;
        $this->notificationService = $notificationService;
        $this->helper = $helper;
        parent::__construct(
            $context,
            $coreRegistry,
            $logger,
            $requestHttp,
            $pageFactory,
            $jsonFactory,
            $layoutFactory
        );
    }

    /**
     * Init layout, menu and breadcrumb
     *
     * @return void
     * @since 1.0.0
     */
    protected function initAction()
    {
        /** @var Page $resultPage */
        $resultPage = $this->pageFactory->create();
        return $resultPage->setActiveMenu('Dem_HelpDesk::helpdesk_case');
    }

    /**
     * Initialize case model instance
     *
     * @return CaseModel|false
     * @since 1.0.0
     */
    protected function initCase()
    {
        $id = $this->getRequest()->getParam('case_id');

        if (!isset($this->caseItem)) {
            $this->caseItem = $this->caseItemRepository->getById($id);
            if (!$this->caseItem) {
                return false;
            }
        }

        /** @var Reply $initialReply */
        $initialReply = $this->caseItem->getInitialReply();

        $this->coreRegistry->register(CaseModel::CURRENT_KEY, $this->caseItem);
        $this->coreRegistry->register(CaseModel::INITIAL_REPLY_KEY, $initialReply);
        return $this->caseItem;
    }
}
