<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Controller;

use Dem\HelpDesk\Controller\Adminhtml\Department as Controller;
use Dem\HelpDesk\Model\Department;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\Http as RequestHttp;
use Magento\Framework\Registry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Controller Adminhtml Department
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class AdminControllerDepartmentTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|Controller
     */
    protected $controller;

    /**
     * @var MockObject|Department
     */
    protected $departmentMock;

    /**
     * Test Department data
     * @var []
     */
    protected $deptData = [
        Department::DEPARTMENT_ID => 1
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->controller = $this->getMockBuilder(Controller::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getDepartmentById',
                'getResultPage',
                'getCoreRegistry',
                'getRequest',
            ])
            ->getMockForAbstractClass();

        $this->departmentMock = $this->objectManager->get(Department::class);
        $this->departmentMock->setData($this->deptData);
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\Department::initAction()
     */
    public function testInitAction()
    {
        /** @var MockObject|Page $resultPage */
        $resultPage = $this->getMockBuilder(Page::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'setActiveMenu'
            ])
            ->getMock();

        $this->controller->expects($this->once())
            ->method('getResultPage')
            ->willReturn($resultPage);

        $resultPage->expects($this->once())
            ->method('setActiveMenu')
            ->willReturn($this->returnSelf());

        $this->assertInstanceOf(Page::class, $this->controller->initAction());
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\Department::initDepartment()
     */
    public function testInitDepartment()
    {
        /** @var MockObject|RequestHttp $requestMock */
        $requestMock = $this->getMockBuilder(RequestHttp::class)
            ->disableOriginalConstructor()
            ->setMethods(['getParam'])
            ->getMock();

        /** @var MockObject|Registry $registryMock */
        $registryMock = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller->expects($this->any())
            ->method('getDepartmentById')
            ->willReturn($this->departmentMock);

        $this->controller->expects($this->any())
            ->method('getRequest')
            ->willReturn($requestMock);

        $requestMock->expects($this->any())
            ->method('getParam')
            ->willReturn(1);

        $this->controller->expects($this->once())
            ->method('getCoreRegistry')
            ->willReturn($registryMock);

        $department = $this->controller->initDepartment();
        $this->assertInstanceOf(Department::class, $department);
        $this->assertEquals($this->deptData[Department::DEPARTMENT_ID], $department->getData(Department::DEPARTMENT_ID));

        $department->unsetData(Department::DEPARTMENT_ID);
        $this->assertFalse($this->controller->initDepartment());
    }
}
