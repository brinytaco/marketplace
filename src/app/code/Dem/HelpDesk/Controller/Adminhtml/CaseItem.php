<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml;

use Dem\Base\Controller\Adminhtml\AbstractAction;
use Dem\HelpDesk\Model\Service\CaseItemManagement;
use Dem\HelpDesk\Model\CaseItem as CaseModel;
use Dem\HelpDesk\Model\CaseItemFactory;
use Dem\HelpDesk\Model\CaseItemRepository;
use Dem\HelpDesk\Model\ReplyRepository;
use Dem\HelpDesk\Model\ReplyFactory;
use Dem\HelpDesk\Model\Service\ReplyManagement;
use Dem\HelpDesk\Model\FollowerRepository;
use Dem\HelpDesk\Model\FollowerFactory;
use Dem\HelpDesk\Model\Service\FollowerManagement;
use Dem\HelpDesk\Model\Service\Notifications;
use Dem\HelpDesk\Helper\Data as Helper;
use Dem\HelpDesk\Model\Source\CaseItem\Department as DepartmentSource;

use Magento\Backend\App\Action;
use Magento\Framework\App\Request\Http as RequestHttp;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\View\Page\Title;

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
     * @var \Dem\HelpDesk\Model\CaseItemRepository
     */
    protected $caseItemRepository;

    /**
     * @var \Dem\HelpDesk\Model\CaseItemFactory
     */
    protected $caseFactory;

    /**
     * @var \Dem\HelpDesk\Model\Service\CaseItemManagement
     */
    protected $caseItemManager;

    /**
     * @var \Dem\HelpDesk\Model\CaseItem
     */
    protected $caseItem;

    /**
     * @var \Dem\HelpDesk\Model\ReplyRepository
     */
    protected $replyRepository;

    /**
     * @var \Dem\HelpDesk\Model\ReplyFactory
     */
    protected $replyFactory;

    /**
     * @var \Dem\HelpDesk\Model\Service\ReplyManagement
     */
    protected $replyManager;

    /**
     * @var \Dem\HelpDesk\Model\FollowerRepository
     */
    protected $followerRepository;

    /**
     * @var \Dem\HelpDesk\Model\FollowerFactory
     */
    protected $followerFactory;

    /**
     * @var \Dem\HelpDesk\Model\Service\FollowerManagement
     */
    protected $followerManager;

    /**
     * @var \Dem\HelpDesk\Model\Service\Notifications
     */
    protected $notificationService;

    /**
     * @var \Dem\HelpDesk\Model\Source\CaseItem\Department
     */
    protected $departmentSource;

    /**
     * @var \Dem\HelpDesk\Model\DepartmentRepository
     */
    protected $departmentRepository;

    /**
     * @var \Dem\HelpDesk\Helper\Data
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
     * @param RedirectFactory $redirectFactory
     * @param CaseItemRepository $caseItemRepository
     * @param CaseItemManagement $caseItemManager
     * @param CaseItemFactory $caseItemFactory
     * @param ReplyRepository $replyRepository
     * @param ReplyManagement $replyManager
     * @param ReplyFactory $replyFactory
     * @param FollowerManagement $followerManager
     * @param FollowerRepository $followerRepository
     * @param FollowerFactory $followerFactory
     * @param Notifications $notificationService
     * @param DepartmentSource $departmentSource
     * @param Helper $helper
     * @codeCoverageIgnore
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
        CaseItemRepository $caseItemRepository,
        CaseItemFactory $caseItemFactory,
        CaseItemManagement $caseItemManager,
        ReplyRepository $replyRepository,
        ReplyManagement $replyManager,
        ReplyFactory $replyFactory,
        FollowerManagement $followerManager,
        FollowerRepository $followerRepository,
        FollowerFactory $followerFactory,
        Notifications $notificationService,
        DepartmentSource $departmentSource,
        Helper $helper
    ) {
        $this->caseItemRepository = $caseItemRepository;
        $this->caseItemManager = $caseItemManager;
        $this->caseItemFactory = $caseItemFactory;
        $this->replyRepository = $replyRepository;
        $this->replyManager = $replyManager;
        $this->replyFactory = $replyFactory;
        $this->followerManager = $followerManager;
        $this->followerRepository = $followerRepository;
        $this->followerFactory = $followerFactory;
        $this->notificationService = $notificationService;
        $this->departmentSource = $departmentSource;
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
     * @return \Magento\Backend\Model\View\Result\Page
     * @since 1.0.0
     */
    public function initAction()
    {
        /** @var Page $resultPage */
        $resultPage = $this->getResultPage();
        return $resultPage->setActiveMenu('Dem_HelpDesk::helpdesk_case');
    }

    /**
     * Initialize case model instance
     *
     * @return \Dem\HelpDesk\Model\CaseItem|false
     * @since 1.0.0
     */
    public function initCase()
    {
        $id = $this->getParam('case_id');

        /** @var \Dem\HelpDesk\Model\CaseItem $caseItem */
        $caseItem = $this->getCaseById($id);
        if (!$caseItem->getId()) {
            return false;
        }

        /** @var \Dem\HelpDesk\Model\Reply $initialReply */
        $initialReply = $caseItem->getInitialReply();

        $this->getCoreRegistry()->register(CaseModel::CURRENT_KEY, $caseItem);
        $this->getCoreRegistry()->register(CaseModel::INITIAL_REPLY_KEY, $initialReply);
        return $caseItem;
    }

    /**
     * Get caseItem by caseId
     *
     * @param int $caseId
     * @return \Dem\HelpDesk\Model\CaseItem
     * @codeCoverageIgnore
     */
    protected function getCaseById($caseId)
    {
        return $this->getCaseItemRepository()->getById($caseId);
    }

    /**
     * Get CaseItemRepository instance
     *
     * @return \Dem\HelpDesk\Model\CaseItemRepository
     * @codeCoverageIgnore
     */
    protected function getCaseItemRepository()
    {
        return $this->caseItemRepository;
    }

    /**
     * Get CaseItemManagement instance
     *
     * @return \Dem\HelpDesk\Model\Service\CaseItemManagement
     * @codeCoverageIgnore
     */
    protected function getCaseItemManager()
    {
        return $this->caseItemManager;
    }

    /**
     * Get CaseItemFactory instance
     *
     * @return \Dem\HelpDesk\Model\CaseItemFactory
     * @codeCoverageIgnore
     */
    protected function getCaseItemFactory()
    {
        return $this->caseItemFactory;
    }

    /**
     * Get ReplyRepository instance
     *
     * @return \Dem\HelpDesk\Model\ReplyRepository
     * @codeCoverageIgnore
     */
    protected function getReplyRepository()
    {
        return $this->replyRepository;
    }

    /**
     * Get ReplyManagement instance
     *
     * @return \Dem\HelpDesk\Model\Service\ReplyManagement
     * @codeCoverageIgnore
     */
    protected function getReplyManager()
    {
        return $this->replyManager;
    }

    /**
     * Get ReplyFactory instance
     *
     * @return \Dem\HelpDesk\Model\ReplyFactory
     * @codeCoverageIgnore
     */
    protected function getReplyFactory()
    {
        return $this->replyFactory;
    }

    /**
     * Get FollowerRepository instance
     *
     * @return \Dem\HelpDesk\Model\FollowerRepository
     * @codeCoverageIgnore
     */
    protected function getFollowerRepository()
    {
        return $this->followerRepository;
    }

    /**
     * Get FollowerManagement instance
     *
     * @return \Dem\HelpDesk\Model\Service\FollowerManagement
     * @codeCoverageIgnore
     */
    protected function getFollowerManager()
    {
        return $this->followerManager;
    }

    /**
     * Get FollowerFactory instance
     *
     * @return \Dem\HelpDesk\Model\FollowerFactory
     * @codeCoverageIgnore
     */
    protected function getFollowerFactory()
    {
        return $this->followerFactory;
    }

    /**
     * Get Notifications instance
     *
     * @return \Dem\HelpDesk\Model\Service\Notifications
     * @codeCoverageIgnore
     */
    protected function getNotificationService()
    {
        return $this->notificationService;
    }

    /**
     * Get Helper instance
     *
     * @return \Dem\HelpDesk\Helper\Data
     * @codeCoverageIgnore
     */
    protected function getHelper()
    {
        return $this->helper;
    }

    /**
     * Get Admin User instance
     *
     * @return \Magento\User\Model\User
     * @codeCoverageIgnore
     */
    protected function getAdminUser()
    {
        return $this->getHelper()->getBackendSession()->getUser();
    }

    /**
     * Get Department source instance
     *
     * @return \Dem\HelpDesk\Model\Source\CaseItem\Department
     * @codeCoverageIgnore
     */
    protected function getDepartmentSource()
    {
        return $this->departmentSource;
    }
}
