<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Block\Adminhtml\CaseItem\Buttons;

use Dem\HelpDesk\Block\Adminhtml\CaseItem\Buttons\CancelButton;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Url;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Block Adminhtml CaseItem BackButton
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class AdminCaseCancelButtonTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * CancelButton
     *
     * @var MockObject|CancelButton
     */
    protected $buttonMock;

    /**
     * URL builder
     *
     * @var MockObject|Url
     */
    protected $urlBuilderMock;


    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->buttonMock = $this->getMockBuilder(CancelButton::class)
            ->disableOriginalConstructor()
            ->setMethods(['getButtonUrl'])
            ->getMock();

        $this->urlBuilderMock = $this->getMockBuilder(Url::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUrl'])
            ->getMock();

    }

    /**
     * @covers \Dem\HelpDesk\Block\Adminhtml\CaseItem\Buttons\CancelButton::getButtonData()
     */
    public function testGetButtonData()
    {
        $testUrl = 'helpdesk/caseitem/back';

        $this->buttonMock->expects($this->once())
            ->method('getButtonUrl')
            ->willReturn($testUrl);

        $this->assertIsArray($this->buttonMock->getButtonData());
    }
}
