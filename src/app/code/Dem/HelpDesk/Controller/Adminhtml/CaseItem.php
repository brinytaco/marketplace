<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\InputException;

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
    const ACTION_RESOURCE = 'Dem_HelpDesk::helpdesk_cases';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magento\Framework\Translate\InlineInterface
     */
    protected $translateInline;

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
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Dem\HelpDesk\Helper\Data
     */
    protected $helper;

    /**
     * @var \Dem\HelpDesk\Model\Service\CaseItemManagement
     */
    protected $caseItemManager;

    /**
     * @var \Dem\HelpDesk\Model\CaseItemFactory
     */
    protected $caseItemFactory;

    /**
     * @var \Dem\HelpDesk\Model\Service\ReplyManagement
     */
    protected $replyManager;

    /**
     * @var \Dem\HelpDesk\Model\ReplyFactory
     */
    protected $replyFactory;

    /**
     * @var \Dem\HelpDesk\Model\Service\FollowerManagement
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
     * @param \Magento\Framework\Translate\InlineInterface $translateInline
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Dem\HelpDesk\Api\CaseItemRepositoryInterface $caseItemRepository
     * @param \Dem\HelpDesk\Model\CaseItemFactory $caseItemFactory
     * @param \Dem\HelpDesk\Model\Service\CaseItemManagement $caseItemManager
     * @param \Dem\HelpDesk\Model\ReplyFactory $replyFactory
     * @param \Dem\HelpDesk\Model\Service\ReplyManagement $replyManager
     * @param \Dem\HelpDesk\Model\FollowerFactory $followerFactory
     * @param \Dem\HelpDesk\Model\Service\FollowerManagement $followerManager
     * @param \Dem\HelpDesk\Model\Service\Notifications $notificationService
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Dem\HelpDesk\Helper\Data $helper
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Translate\InlineInterface $translateInline,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Dem\HelpDesk\Api\CaseItemRepositoryInterface $caseItemRepository,
        \Dem\HelpDesk\Model\CaseItemFactory $caseItemFactory,
        \Dem\HelpDesk\Model\Service\CaseItemManagement $caseItemManager,
        \Dem\HelpDesk\Model\ReplyFactory $replyFactory,
        \Dem\HelpDesk\Model\Service\ReplyManagement $replyManager,
        \Dem\HelpDesk\Model\FollowerFactory $followerFactory,
        \Dem\HelpDesk\Model\Service\FollowerManagement $followerManager,
        \Dem\HelpDesk\Model\Service\Notifications $notificationService,
        \Psr\Log\LoggerInterface $logger,
        \Dem\HelpDesk\Helper\Data $helper
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->translateInline = $translateInline;
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
        $this->logger = $logger;
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
        $frontendLabel = $this->helper->getConfiguredFrontendLabel();

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Dem_HelpDesk::helpdesk_cases');
        $resultPage->addBreadcrumb($frontendLabel, $frontendLabel);
        $resultPage->addBreadcrumb(__('Cases'), __('Cases'));
        return $resultPage;
    }

    /**
     * Initialize order model instance
     *
     * @return \Dem\HelpDesk\Api\Data\CaseItemRepositoryInterface|false
     * @since 1.0.0
     */
    protected function _initCase()
    {
        $id = $this->getRequest()->getParam('case_id');
        try {
            $case = $this->caseItemRepository->getById($id);
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('The requested case no longer exists'));
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
            return false;
        } catch (InputException $e) {
            $this->messageManager->addErrorMessage(__('The requested case no longer exists'));
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
            return false;
        }
        $this->coreRegistry->register(\Dem\HelpDesk\Model\CaseItem::CURRENT_KEY, $case);
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
