<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Block\Adminhtml\CaseItem\Buttons;

use Dem\HelpDesk\Block\Adminhtml\CaseItem\Buttons\SaveButton;
use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use Magento\Framework\Registry;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Block Adminhtml CaseItem SaveButton
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class AdminCaseSaveButtonTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * SaveButton
     *
     * @var MockObject|SaveButton
     */
    protected $buttonMock;

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->buttonMock = $this->getMockBuilder(SaveButton::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getCurrentCase'
            ])
            ->getMock();
    }

    /**
     * @covers \Dem\HelpDesk\Block\Adminhtml\CaseItem\Buttons\SaveButton::getButtonData()
     */
    public function testGetButtonData()
    {
        $this->buttonMock->expects($this->once())
            ->method('getCurrentCase')
            ->willReturn(null);

        $this->assertIsArray($this->buttonMock->getButtonData());
    }


    /**
     * @covers \Dem\HelpDesk\Block\Adminhtml\CaseItem\Buttons\SaveButton::getButtonData()
     */
    public function testGetButtonDataEmpty()
    {
        $this->buttonMock->expects($this->once())
            ->method('getCurrentCase')
            ->willReturn(true);

        $this->assertEquals([], $this->buttonMock->getButtonData());
    }
}
