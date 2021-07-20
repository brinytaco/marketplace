<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Controller\Department;

use Dem\HelpDesk\Controller\Adminhtml\Department\Edit as Controller;
use Dem\HelpDesk\Model\Department;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\View\Page\Title;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Controller Adminhtml Department Edit
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class AdminControllerDepartmentEditTest extends TestCase
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
        Department::DEPARTMENT_ID => 1,
        Department::NAME => 'Default Department'
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->controller = $this->getMockBuilder(Controller::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'initDepartment',
                'initAction',
                'getPageTitle',
                'getRedirect',
                'getMessageManager',
            ])
            ->getMock();

        $this->departmentMock = $this->getMockBuilder(Department::class)
            ->disableOriginalConstructor()
            ->setMethods([
            ])
            ->getMock();

        $this->departmentMock->setData($this->deptData);
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\Department\Edit::execute()
     */
    public function testExecutePage()
    {
        /** @var Title|MockObject $title */
        /** @var Page|MockObject $resultPage */
        $title = $this->objectManager->get(Title::class);
        $resultPage = $this->objectManager->get(Page::class);

        $this->controller->expects($this->once())
            ->method('initDepartment')
            ->willReturn($this->departmentMock);

        $this->controller->expects($this->once())
            ->method('initAction')
            ->willReturn($resultPage);

        $this->controller->expects($this->once())
            ->method('getPageTitle')
            ->willReturn($title);

        $this->assertInstanceOf(Page::class, $this->controller->execute());
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\Department\Edit::execute()
     */
    public function testExecuteRedirect()
    {
        /** @var MockObject|Redirect $resultRedirect */
        $resultRedirect = $this->getMockBuilder(Redirect::class)
            ->disableOriginalConstructor()
            ->setMethods(['setPath'])
            ->getMock();

        /** @var MessageManagerInterface|MockObject $messageManager */
        $messageManager = $this->objectManager->get(MessageManagerInterface::class);

        $this->controller->expects($this->once())
            ->method('initDepartment')
            ->willReturn(false);

        $this->controller->expects($this->once())
            ->method('getRedirect')
            ->willReturn($resultRedirect);

        $this->controller->expects($this->once())
            ->method('getMessageManager')
            ->willReturn($messageManager);

        $resultRedirect->expects($this->once())
            ->method('setPath')
            ->willReturn($this->returnSelf());

        $this->assertInstanceOf(Redirect::class, $this->controller->execute());
    }
}
