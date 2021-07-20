<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * HelpDesk - Adminhtml Abstract Department Controller
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
abstract class Department extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ACTION_RESOURCE = 'Dem_HelpDesk::helpdesk_department';

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
     * @var \Dem\HelpDesk\Api\DepartmentRepositoryInterface
     */
    protected $departmentRepository;

    /**
     * @var \Dem\HelpDesk\Helper\Data
     */
    protected $helper;

    /**
     * @var \Dem\HelpDesk\Model\Service\DepartmentManagementInterface
     */
    protected $departmentManager;

    /**
     * @var \Dem\HelpDesk\Model\DepartmentFactory
     */
    protected $departmentFactory;

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
     * @param \Dem\HelpDesk\Api\DepartmentRepositoryInterface $departmentRepository
     * @param \Dem\HelpDesk\Api\DepartmentManagementInterface $departmentManager
     * @param \Dem\HelpDesk\Model\DepartmentFactory $departmentFactory
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
        \Dem\HelpDesk\Api\DepartmentRepositoryInterface $departmentRepository,
        \Dem\HelpDesk\Api\DepartmentManagementInterface $departmentManager,
        \Dem\HelpDesk\Model\DepartmentFactory $departmentFactory,
        \Dem\HelpDesk\Helper\Data $helper
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->logger = $logger;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->resultRawFactory = $resultRawFactory;
        $this->departmentRepository = $departmentRepository;
        $this->departmentManager = $departmentManager;
        $this->departmentFactory = $departmentFactory;
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
        $resultPage->setActiveMenu('Dem_HelpDesk::helpdesk_department');
        $resultPage->addBreadcrumb($frontendLabel, $frontendLabel);
        $resultPage->addBreadcrumb(__('Departments'), __('Departments'));

        $resultPage->getConfig()->getTitle()->prepend($frontendLabel);
        return $resultPage;
    }

    /**
     * Initialize department model instance
     *
     * @return \Dem\HelpDesk\Api\Data\DepartmentInterface|false
     * @since 1.0.0
     */
    protected function _initDepartment()
    {
        $id = $this->getRequest()->getParam('department_id');
        $objectStr = __('department');
        try {
            $department = $this->departmentRepository->getById($id);
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('The requested %1 no longer exists', $objectStr));
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
            return false;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('The requested %1 no longer exists', $objectStr));
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
            return false;
        }
        $this->coreRegistry->register(\Dem\HelpDesk\Model\Department::CURRENT_KEY, $department);
        return $department;
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
