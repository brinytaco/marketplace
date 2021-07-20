<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml;

use Dem\HelpDesk\Model\Department as DepartmentModel;
use Dem\Base\Controller\Adminhtml\AbstractAction;
use Dem\HelpDesk\Model\DepartmentRepository;
use Dem\HelpDesk\Model\Service\DepartmentManagement;
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
     * @var DepartmentRepository
     */
    protected $departmentRepository;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var DepartmentManagement
     */
    protected $departmentManager;

    /**
     * @var DepartmentModel
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
     * @param DepartmentRepository $departmentRepository
     * @param DepartmentManagement $departmentManager
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
        DepartmentRepository $departmentRepository,
        DepartmentManagement $departmentManager,
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
     * @return DepartmentModel|false
     * @since 1.0.0
     */
    protected function initDepartment()
    {
        $id = $this->getRequest()->getParam('department_id');

        $department = $this->departmentRepository->getById($id);
        if (!$department) {
            return false;
        }

        $this->coreRegistry->register(DepartmentModel::CURRENT_KEY, $department);
        return $department;
    }
}
