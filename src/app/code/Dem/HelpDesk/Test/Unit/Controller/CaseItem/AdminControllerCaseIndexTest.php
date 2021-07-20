<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Controller\CaseItem;

use Dem\HelpDesk\Controller\Adminhtml\CaseItem\Index as Controller;
use Dem\HelpDesk\Model\CaseItem;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Page\Title;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Controller Adminhtml CaseItem Index
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class AdminControllerCaseIndexTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|Controller
     */
    protected $controller;

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->controller = $this->getMockBuilder(Controller::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'initAction',
                'getPageTitle',
            ])
            ->getMock();
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\CaseItem\Index::execute()
     */
    public function testExecute()
    {
        /** @var Title|MockObject $title */
        /** @var Page|MockObject $resultPage */
        $title = $this->objectManager->get(Title::class);
        $resultPage = $this->objectManager->get(Page::class);

        $this->controller->expects($this->once())
            ->method('initAction')
            ->willReturn($resultPage);

        $this->controller->expects($this->once())
            ->method('getPageTitle')
            ->willReturn($title);

        $this->assertInstanceOf(Page::class, $this->controller->execute());
    }
}
