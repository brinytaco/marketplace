<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml;

use Dem\Base\Controller\Adminhtml\AbstractAction;
use Dem\HelpDesk\Api\DepartmentRepositoryInterface;
use Dem\HelpDesk\Api\DepartmentManagementInterface;
use Dem\HelpDesk\Helper\Data as Helper;
use Dem\HelpDesk\Api\Data\DepartmentInterface;

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
 * HelpDesk - Adminhtml Abstract Department Controller
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
abstract class Department extends AbstractAction
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ACTION_RESOURCE = 'Dem_HelpDesk::helpdesk_department';

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
     * @var Department
     */
    protected $department;

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
     * @param DepartmentRepositoryInterface $departmentRepository
     * @param DepartmentManagementInterface $departmentManager
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
        DepartmentRepositoryInterface $departmentRepository,
        DepartmentManagementInterface $departmentManager,
        Helper $helper
    ) {
        $this->departmentRepository = $departmentRepository;
        $this->departmentManager = $departmentManager;
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
     * @return Page
     * @since 1.0.0
     */
    protected function initAction()
    {
        /** @var Page $resultPage */
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Dem_HelpDesk::helpdesk_department');
        return $resultPage;
    }

    /**
     * Initialize department model instance
     *
     * @return DepartmentInterface|false
     * @since 1.0.0
     */
    protected function initDepartment()
    {
        $id = $this->getRequest()->getParam('department_id');

        /** @var \Dem\HelpDesk\Model\Department $department */
        $department = $this->departmentRepository->getById($id);
        if (!$department) {
            return false;
        }

        $this->coreRegistry->register(\Dem\HelpDesk\Model\Department::CURRENT_KEY, $department);
        return $department;
    }
}
