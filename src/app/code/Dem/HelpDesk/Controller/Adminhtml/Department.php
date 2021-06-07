<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\Result\RedirectFactory;
use Dem\HelpDesk\Api\DepartmentRepositoryInterface;
use Dem\HelpDesk\Api\DepartmentManagementInterface;
use Dem\HelpDesk\Model\DepartmentFactory;
use Dem\HelpDesk\Helper\Data as Helper;
use Dem\HelpDesk\Api\Data\DepartmentInterface;

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
     * @var DepartmentRepositoryInterface
     */
    protected $departmentRepository;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var DepartmentManagementInterface
     */
    protected $departmentManager;

    /**
     * @var DepartmentFactory
     */
    protected $departmentFactory;

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
     * @param DepartmentRepositoryInterface $departmentRepository
     * @param DepartmentManagementInterface $departmentManager
     * @param DepartmentFactory $departmentFactory
     * @param Helper $helper
     */
    public function __construct(
        Action\Context $context,
        Registry $coreRegistry,
        LoggerInterface $logger,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        LayoutFactory $resultLayoutFactory,
        RawFactory $resultRawFactory,
        DepartmentRepositoryInterface $departmentRepository,
        DepartmentManagementInterface $departmentManager,
        DepartmentFactory $departmentFactory,
        Helper $helper
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
     * @return Page
     * @since 1.0.0
     */
    protected function _initAction()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Dem_HelpDesk::helpdesk_department');
        return $resultPage;
    }

    /**
         * Initialize department model instance
         *
         * @return DepartmentInterface|false
         * @since 1.0.0
         */
    protected function _initDepartment()
    {
        $id = $this->getRequest()->getParam('department_id');
        $objectStr = __('department');
        try {
            /** @var \Dem\HelpDesk\Model\Department $department */
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
        $this->coreRegistry->register(Department::CURRENT_KEY, $department);
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
