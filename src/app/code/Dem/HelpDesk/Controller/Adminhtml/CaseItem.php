<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\NoSuchEntityException;

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
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Dem\HelpDesk\Api\CaseItemRepositoryInterface
     */
    protected $caseItemRepository;

    /**
     * @var \Dem\HelpDesk\Api\DepartmentRepositoryInterface
     */
    protected $departmentRepository;

    /**
     * @var \Dem\HelpDesk\Helper\Data
     */
    protected $helper;

    /**
     * @var \Dem\HelpDesk\Api\CaseItemManagementInterface
     */
    protected $caseItemManager;

    /**
     * @var \Dem\HelpDesk\Model\CaseItemFactory
     */
    protected $caseItemFactory;

    /**
     * @var \Dem\HelpDesk\Api\ReplyManagementInterface
     */
    protected $replyManager;

    /**
     * @var \Dem\HelpDesk\Model\ReplyFactory
     */
    protected $replyFactory;

    /**
     * @var \Dem\HelpDesk\Api\FollowerManagementInterface
     */
    protected $followerManager;

    /**
     * @var \Dem\HelpDesk\Model\FollowerFactory
     */
    protected $followerFactory;

    /**
     * @var \Dem\HelpDesk\Model\Service\Notifications
     */
    protected $notificationService;

    /**
     * Data constructor.
     *
     * @param Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Dem\HelpDesk\Api\CaseItemRepositoryInterface $caseItemRepository
     * @param \Dem\HelpDesk\Model\CaseItemFactory $caseItemFactory
     * @param \Dem\HelpDesk\Api\CaseItemManagementInterface $caseItemManager
     * @param \Dem\HelpDesk\Model\ReplyFactory $replyFactory
     * @param \Dem\HelpDesk\Api\ReplyManagementInterface $replyManager
     * @param \Dem\HelpDesk\Model\FollowerFactory $followerFactory
     * @param \Dem\HelpDesk\Api\FollowerManagementInterface $followerManager
     * @param \Dem\HelpDesk\Model\Service\Notifications $notificationService
     * @param \Dem\HelpDesk\Helper\Data $helper
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Dem\HelpDesk\Api\CaseItemRepositoryInterface $caseItemRepository,
        \Dem\HelpDesk\Model\CaseItemFactory $caseItemFactory,
        \Dem\HelpDesk\Api\CaseItemManagementInterface $caseItemManager,
        \Dem\HelpDesk\Model\ReplyFactory $replyFactory,
        \Dem\HelpDesk\Api\ReplyManagementInterface $replyManager,
        \Dem\HelpDesk\Model\FollowerFactory $followerFactory,
        \Dem\HelpDesk\Api\FollowerManagementInterface $followerManager,
        \Dem\HelpDesk\Model\Service\Notifications $notificationService,
        \Dem\HelpDesk\Helper\Data $helper
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
     * @return \Magento\Backend\Model\View\Result\Page
     * @since 1.0.0
     */
    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Dem_HelpDesk::helpdesk_case');
        return $resultPage;
    }

    /**
     * Initialize case model instance
     *
     * @return \Dem\HelpDesk\Api\Data\CaseItemInterface|false
     * @since 1.0.0
     */
    protected function _initCase()
    {
        $id = $this->getRequest()->getParam('case_id');
        $objectStr = __('case');
        try {
            $case = $this->caseItemRepository->getById($id);
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('The requested %1 no longer exists', $objectStr));
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
            return false;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('The requested %1 no longer exists', $objectStr));
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
            return false;
        }
        $this->coreRegistry->register(\Dem\HelpDesk\Model\CaseItem::CURRENT_KEY, $case);
        $this->coreRegistry->register(\Dem\HelpDesk\Model\CaseItem::INITIAL_REPLY_KEY, $case->getInitialReply());
        return $case;
    }

    /**
     * Check is valid post request
     *
     * @return bool
     * @since 1.0.0
     */
    protected function isValidPostRequest()
    {
        $formKeyIsValid = $this->_formKeyValidator->validate($this->getRequest());
        $isPost = $this->getRequest()->isPost();
        return ($formKeyIsValid && $isPost);
    }
}
