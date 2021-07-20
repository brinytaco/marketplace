<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Controller;

use Dem\HelpDesk\Controller\Adminhtml\CaseItem as Controller;
use Dem\HelpDesk\Model\CaseItem;
use Dem\HelpDesk\Model\Reply;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\Http as RequestHttp;
use Magento\Framework\Registry;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Controller Adminhtml CaseItem
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class AdminControllerCaseItemTest extends TestCase
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
     * @var MockObject|CaseItem
     */
    protected $caseMock;

    /**
     * Test Case data
     * @var []
     */
    protected $caseData = [
        CaseItem::CASE_ID => 1
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->controller = $this->getMockBuilder(Controller::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getCaseById',
                'getResultPage',
                'getCoreRegistry',
                'getCaseItemRepository',
                'getCaseItemManager',
                'getReplyManager',
                'getFollowerManager',
                'getNotificationService',
                'getHelper',
                'getParam',
            ])
            ->getMockForAbstractClass();

        $this->caseMock = $this->getMockBuilder(CaseItem::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getInitialReply'
            ])
            ->getMock();

        $this->caseMock->setData($this->caseData);
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\CaseItem::initAction()
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
     * @covers \Dem\HelpDesk\Controller\Adminhtml\CaseItem::initCase()
     */
    public function testInitCase()
    {
        /** @var MockObject|Registry $registryMock */
        $registryMock = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller->expects($this->any())
            ->method('getCaseById')
            ->willReturn($this->caseMock);

        $this->controller->expects($this->any())
            ->method('getParam')
            ->willReturn(1);

        $reply = $this->objectManager->create(Reply::class);

        $this->caseMock->expects($this->once())
            ->method('getInitialReply')
            ->willReturn($reply);

        $this->controller->expects($this->exactly(2))
            ->method('getCoreRegistry')
            ->willReturn($registryMock);

        $caseItem = $this->controller->initCase();
        $this->assertInstanceOf(CaseItem::class, $caseItem);
        $this->assertEquals($this->caseData[CaseItem::CASE_ID], $caseItem->getData(CaseItem::CASE_ID));

        $caseItem->unsetData(CaseItem::CASE_ID);
        $this->assertFalse($this->controller->initCase());
    }
}
