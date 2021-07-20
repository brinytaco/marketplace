<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml;

use Dem\Base\Controller\Adminhtml\AbstractAction;
use Dem\HelpDesk\Api\CaseItemRepositoryInterface;
use Dem\HelpDesk\Api\Data\CaseItemInterface;
use Dem\HelpDesk\Api\CaseItemManagementInterface;
use Dem\HelpDesk\Model\ReplyFactory;
use Dem\HelpDesk\Api\ReplyManagementInterface;
use Dem\HelpDesk\Model\FollowerFactory;
use Dem\HelpDesk\Api\FollowerManagementInterface;
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
     * @var CaseItemRepositoryInterface
     */
    protected $caseItemRepository;

    /**
     * @var DepartmentRepositoryInterface
     */
    protected $departmentRepository;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var CaseItemManagementInterface
     */
    protected $caseItemManager;

    /**
     * @var CaseModel
     */
    protected $caseItem;

    /**
     * @var ReplyManagementInterface
     */
    protected $replyManager;

    /**
     * @var ReplyFactory
     */
    protected $replyFactory;

    /**
     * @var FollowerManagementInterface
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
     * Data constructor.
     *
     * @param Action\Context $context
     * @param Registry $coreRegistry
     * @param LoggerInterface $logger
     * @param RequestHttp $requestHttp
     * @param PageFactory $pageFactory
     * @param JsonFactory $jsonFactory
     * @param LayoutFactory $layoutFactory
     * @param RedirectFactory $redirectFactory
     * @param CaseItemRepositoryInterface $caseItemRepository
     * @param CaseItemManagementInterface $caseItemManager
     * @param ReplyFactory $replyFactory
     * @param ReplyManagementInterface $replyManager
     * @param FollowerFactory $followerFactory
     * @param FollowerManagementInterface $followerManager
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
        RedirectFactory $redirectFactory,
        CaseItemRepositoryInterface $caseItemRepository,
        CaseItemManagementInterface $caseItemManager,
        ReplyFactory $replyFactory,
        ReplyManagementInterface $replyManager,
        FollowerFactory $followerFactory,
        FollowerManagementInterface $followerManager,
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
            $layoutFactory,
            $redirectFactory
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
     * @return CaseItemInterface|false
     * @since 1.0.0
     */
    protected function initCase()
    {
        $id = $this->getRequest()->getParam('case_id');

        /** @var \Dem\HelpDesk\Model\CaseItem $case */
        $case = $this->caseItemRepository->getById($id);
        if (!$case) {
            return false;
        }

        /** @var \Dem\HelpDesk\Model\Reply $initialReply */
        $initialReply = $case->getInitialReply();

        $this->coreRegistry->register($case::CURRENT_KEY, $case);
        $this->coreRegistry->register($case::INITIAL_REPLY_KEY, $initialReply);
        return $case;
    }
}
