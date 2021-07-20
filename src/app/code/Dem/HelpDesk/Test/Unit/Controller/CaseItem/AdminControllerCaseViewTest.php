<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Controller\CaseItem;

use Dem\HelpDesk\Controller\Adminhtml\CaseItem\View as Controller;
use Dem\HelpDesk\Model\CaseItem;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\View\Page\Title;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Controller Adminhtml CaseItem View
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class AdminControllerCaseViewTest extends TestCase
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
        CaseItem::CASE_ID => 1,
        CaseItem::CASE_NUMBER => '001-000001'
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->controller = $this->getMockBuilder(Controller::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'initCase',
                'initAction',
                'getPageTitle',
                'getRedirect',
                'getMessageManager',
            ])
            ->getMock();

        $this->caseMock = $this->getMockBuilder(CaseItem::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getInitialReply'
            ])
            ->getMock();

        $this->caseMock->setData($this->caseData);
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\CaseItem\View::execute()
     */
    public function testExecutePage()
    {
        /** @var Title|MockObject $title */
        /** @var Page|MockObject $resultPage */
        $title = $this->objectManager->get(Title::class);
        $resultPage = $this->objectManager->get(Page::class);

        $this->controller->expects($this->once())
            ->method('initCase')
            ->willReturn($this->caseMock);

        $this->controller->expects($this->once())
            ->method('initAction')
            ->willReturn($resultPage);

        $this->controller->expects($this->once())
            ->method('getPageTitle')
            ->willReturn($title);

        $this->assertInstanceOf(Page::class, $this->controller->execute());
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\CaseItem\View::execute()
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
            ->method('initCase')
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
