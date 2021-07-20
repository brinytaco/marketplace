<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Controller\CaseItem;

use Dem\HelpDesk\Controller\Adminhtml\CaseItem\DepartmentList as Controller;
use Dem\HelpDesk\Model\Source\CaseItem\Department as DepartmentSource;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\Http as RequestHttp;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Registry;
use Magento\Store\Api\Data\WebsiteInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Dem\HelpDesk\Exception as HelpDeskException;

/**
 * HelpDesk Unit Test - Controller Adminhtml CaseItem DepartmentList
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class AdminControllerCaseDepartmentListTest extends TestCase
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
     * @var MockObject|Json
     */
    protected $resultJson;

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->controller = $this->getMockBuilder(Controller::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getResultJson',
                'getWebsiteById',
                'getDepartmentSource',
                'isAjax',
                'getCoreRegistry',
                'getParam',
            ])
            ->getMock();


        $this->resultJson = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->setMethods(['setData'])
            ->getMock();
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\CaseItem\DepartmentList::execute()
     */
    public function testExecuteResult()
    {
        /** @var WebsiteInterface|MockObject $website */
        $website = $this->objectManager->get(WebsiteInterface::class);

        /** @var MockObject|DepartmentSource $deptSource */
        $deptSource = $this->getMockBuilder(DepartmentSource::class)
            ->disableOriginalConstructor()
            ->setMethods(['toOptionArray'])
            ->getMock();

        /** @var MockObject|Registry $registryMock */
        $registryMock = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller->expects($this->any())
            ->method('getResultJson')
            ->willReturn($this->resultJson);

        $this->controller->expects($this->any())
            ->method('isAjax')
            ->willReturn(true);

        $this->controller->expects($this->once())
            ->method('getParam')
            ->willReturn(1);

        $this->controller->expects($this->once())
            ->method('getWebsiteById')
            ->willReturn($website);

        $this->controller->expects($this->once())
            ->method('getCoreRegistry')
            ->willReturn($registryMock);

        $this->controller->expects($this->once())
            ->method('getDepartmentSource')
            ->willReturn($deptSource);

        $deptOptions = [[
            'label' => 'Default Department',
            'value' => \Dem\HelpDesk\Helper\Config::HELPDESK_DEPARTMENT_DEFAULT_ID
        ]];

        $deptSource->expects($this->once())
            ->method('toOptionArray')
            ->willReturn($deptOptions);

        $this->resultJson->expects($this->once())
            ->method('setData')
            ->willReturn($this->returnSelf());

        $this->assertInstanceOf(Json::class, $this->controller->execute());
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\CaseItem\DepartmentList::execute()
     */
    public function testExecuteNotAjax()
    {
        $this->controller->expects($this->any())
            ->method('getResultJson')
            ->willReturn($this->resultJson);

        $this->controller->expects($this->any())
            ->method('isAjax')
            ->willReturn(false);

        $this->assertInstanceOf(Json::class, $this->controller->execute());
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\CaseItem\DepartmentList::execute()
     */
    public function testExecuteException()
    {
        /** @var WebsiteInterface|MockObject $website */
        $website = $this->objectManager->get(WebsiteInterface::class);

        /** @var MockObject|DepartmentSource $deptSource */
        $deptSource = $this->getMockBuilder(DepartmentSource::class)
            ->disableOriginalConstructor()
            ->setMethods(['toOptionArray'])
            ->getMock();

        /** @var MockObject|Registry $registryMock */
        $registryMock = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller->expects($this->any())
            ->method('getResultJson')
            ->willReturn($this->resultJson);

        $this->controller->expects($this->any())
            ->method('isAjax')
            ->willReturn(true);

        $this->controller->expects($this->once())
            ->method('getParam')
            ->willReturn(1);

        $this->controller->expects($this->once())
            ->method('getWebsiteById')
            ->willThrowException(new HelpDeskException(__('exception')));

        $this->resultJson->expects($this->once())
            ->method('setData')
            ->willReturn($this->returnSelf());

        $this->assertInstanceOf(Json::class, $this->controller->execute());
    }

}
