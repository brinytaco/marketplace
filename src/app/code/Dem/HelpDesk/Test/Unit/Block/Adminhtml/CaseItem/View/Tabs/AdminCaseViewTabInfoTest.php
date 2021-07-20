<?php

declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Block\Adminhtml\CaseItem\View\Tabs;

use Dem\HelpDesk\Block\Adminhtml\CaseItem\View\Tab\Info;
use Dem\HelpDesk\Model\CaseItem;
use Dem\HelpDesk\Model\User as HelpDeskUser;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Phrase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Block Adminhtml CaseItem View Tab Info
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class AdminCaseViewTabInfoTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * Info
     *
     * @var MockObject|Info
     */
    protected $infoMock;

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->infoMock = $this->getMockBuilder(Info::class)
            ->disableOriginalConstructor()
            ->setMethods([
                '__',
                'getCase',
                'getUrl',
                'getCaseManager',
                'getVisibleReplies'
            ])
            ->getMock();
    }

    /**
     * @covers \Dem\HelpDesk\Block\Adminhtml\CaseItem\View\Tab\Info::getOtherUserRepliesCount()
     */
    public function testgetOtherUserRepliesCount()
    {
        $this->infoMock->expects($this->once())
            ->method('getVisibleReplies')
            ->willReturn([]);

        $this->assertEquals(0, $this->infoMock->getOtherUserRepliesCount());
    }
}
