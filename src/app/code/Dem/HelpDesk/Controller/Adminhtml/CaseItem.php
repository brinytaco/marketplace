<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Dem\HelpDesk\Api\CaseItemRepositoryInterface;
use Dem\HelpDesk\Api\Data\CaseItemInterface;
use Dem\HelpDesk\Model\CaseItemFactory;
use Dem\HelpDesk\Api\CaseItemManagementInterface;
use Dem\HelpDesk\Model\ReplyFactory;
use Dem\HelpDesk\Api\ReplyManagementInterface;
use Dem\HelpDesk\Model\FollowerFactory;
use Dem\HelpDesk\Api\FollowerManagementInterface;
use Dem\HelpDesk\Model\Service\Notifications;
use Dem\HelpDesk\Helper\Data;

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
abstract class CaseItem extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ACTION_RESOURCE = 'Dem_HelpDesk::helpdesk_cases';

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var CaseItemRepositoryInterface
     */
    protected $caseItemRepository;

    /**
     * @var DepartmentRepositoryInterface
     */
    protected $departmentRepository;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var CaseItemManagementInterface
     */
    protected $caseItemManager;

    /**
     * @var CaseItemFactory
     */
    protected $caseItemFactory;

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
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param LayoutFactory $resultLayoutFactory
     * @param RawFactory $resultRawFactory
     * @param CaseItemRepositoryInterface $caseItemRepository
     * @param CaseItemFactory $caseItemFactory
     * @param CaseItemManagementInterface $caseItemManager
     * @param ReplyFactory $replyFactory
     * @param ReplyManagementInterface $replyManager
     * @param FollowerFactory $followerFactory
     * @param FollowerManagementInterface $followerManager
     * @param Notifications $notificationService
     * @param Data $helper
     */
    public function __construct(
        Action\Context $context,
        Registry $coreRegistry,
        LoggerInterface $logger,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        LayoutFactory $resultLayoutFactory,
        RawFactory $resultRawFactory,
        CaseItemRepositoryInterface $caseItemRepository,
        CaseItemFactory $caseItemFactory,
        CaseItemManagementInterface $caseItemManager,
        ReplyFactory $replyFactory,
        ReplyManagementInterface $replyManager,
        FollowerFactory $followerFactory,
        FollowerManagementInterface $followerManager,
        Notifications $notificationService,
        Data $helper
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->logger = $logger;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->resultRawFactory = $resultRawFactory;
        $this->caseItemRepository = $caseItemRepository;
        $this->caseItemFactory = $caseItemFactory;
        $this->caseItemManager = $caseItemManager;
        $this->replyManager = $replyManager;
        $this->replyFactory = $replyFactory;
        $this->followerManager = $followerManager;
        $this->followerFactory = $followerFactory;
        $this->notificationService = $notificationService;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * Init layout, menu and breadcrumb
     *
     * @return Page
     * @since 1.0.0
     */
    protected function _initAction()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Dem_HelpDesk::helpdesk_case');
        return $resultPage;
    }

    /**
     * Initialize case model instance
     *
     * @return CaseItemInterface|false
     * @since 1.0.0
     */
    protected function _initCase()
    {
        $id = $this->getRequest()->getParam('case_id');
        $objectStr = __('case');
        try {
            /** @var \Dem\HelpDesk\Model\CaseItem $case */
            $case = $this->caseItemRepository->getById($id);
            if (!$case) {
                throw new NoSuchEntityException(__('The requested %1 no longer exists', $objectStr));
            }
            /** @var \Dem\HelpDesk\Model\Reply $initialReply */
            $initialReply = $case->getInitialReply();
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
            // Redirect ?
            return false;
        }
        $this->coreRegistry->register($case::CURRENT_KEY, $case);
        $this->coreRegistry->register($case::INITIAL_REPLY_KEY, $initialReply);
        return $case;
    }

    /***********************************************************************************/
    /** @todo Move to Base */
    /***********************************************************************************/

    /**
     * Check is valid post request
     *
     * @todo Move to abstract base
     *
     * @return bool
     * @since 1.0.0
     */
    public function isValidPostRequest()
    {
        return ($this->isPostRequest() && $this->isFormKeyValid());
    }

    /**
     * Check is post request
     *
     * @todo Move to abstract base
     *
     * @return bool
     * @since 1.0.0
     */
    public function isPostRequest()
    {
        return (!strcasecmp($_SERVER['REQUEST_METHOD'], 'POST'));
    }

    /**
     * Check is valid form key
     *
     * @todo Move to abstract base
     *
     * @return bool
     * @since 1.0.0
     */
    public function isFormKeyValid()
    {
        return ($this->_formKeyValidator->validate($this->getRequest()));
    }
}
