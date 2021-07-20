<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml;

use Dem\Base\Controller\Adminhtml\AbstractAction;
use Dem\HelpDesk\Model\Service\DepartmentManagement;
use Dem\HelpDesk\Model\Department as DepartmentModel;
use Dem\HelpDesk\Model\DepartmentRepository;
use Dem\HelpDesk\Model\DepartmentFactory;
use Dem\HelpDesk\Helper\Data as Helper;

use Magento\Backend\App\Action;
use Magento\Framework\App\Request\Http as RequestHttp;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\View\Page\Title;

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
     * @var \Dem\HelpDesk\Model\DepartmentRepository
     */
    protected $departmentRepository;

    /**
     * @var \Dem\HelpDesk\Model\Service\DepartmentManagement
     */
    protected $departmentManager;

    /**
     * @var \Dem\HelpDesk\Model\DepartmentFactory
     */
    protected $departmentFactory;

    /**
     * @var \Dem\HelpDesk\Model\Department
     */
    protected $department;

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
     * @param DepartmentRepository $departmentRepository
     * @param DepartmentManagement $departmentManager
     * @param DepartmentFactory $departmentFactory
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
        DepartmentRepository $departmentRepository,
        DepartmentManagement $departmentManager,
        DepartmentFactory $departmentFactory,
        Helper $helper
    ) {
        $this->departmentRepository = $departmentRepository;
        $this->departmentManager = $departmentManager;
        $this->departmentFactory = $departmentFactory;
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
        return $resultPage->setActiveMenu('Dem_HelpDesk::helpdesk_department');
    }

    /**
     * Initialize department model instance
     *
     * @return \Dem\HelpDesk\Model\Department|false
     * @since 1.0.0
     */
    public function initDepartment()
    {
        $id = $this->getRequest()->getParam('department_id');

        /** @var \Dem\HelpDesk\Model\Department $department */
        $department = $this->getDepartmentById($id);
        if (!$department->getId()) {
            return false;
        }

        $this->getCoreRegistry()->register(DepartmentModel::CURRENT_KEY, $department);
        return $department;
    }

    /**
     * Get Department by deptId
     *
     * @param int $deptId
     * @return \Dem\HelpDesk\Model\Department
     * @codeCoverageIgnore
     */
    protected function getDepartmentById($deptId)
    {
        return $this->getDepartmentRepository()->getById($deptId);
    }

    /**
     * Get DepartmentRepository instance
     *
     * @return \Dem\HelpDesk\Model\DepartmentRepository
     * @codeCoverageIgnore
     */
    protected function getDepartmentRepository()
    {
        return $this->departmentRepository;
    }

    /**
     * Get DepartmentManagement instance
     *
     * @return \Dem\HelpDesk\Model\Service\DepartmentManagement
     * @codeCoverageIgnore
     */
    protected function getDepartmentManager()
    {
        return $this->departmentManager;
    }

    /**
     * Get DepartmentFactory instance
     *
     * @return \Dem\HelpDesk\Model\DepartmentFactory
     * @codeCoverageIgnore
     */
    protected function getDepartmentFactory()
    {
        return $this->departmentFactory;
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
}
