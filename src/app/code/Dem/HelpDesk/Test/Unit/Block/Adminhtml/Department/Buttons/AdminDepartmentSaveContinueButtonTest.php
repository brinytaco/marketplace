<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Block\Adminhtml\Department\Buttons;

use Dem\HelpDesk\Block\Adminhtml\Department\Buttons\SaveAndContinueButton;
use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Block Adminhtml Department SaveButton
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class AdminDepartmentSaveContinueButtonTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * SaveButton
     *
     * @var SaveButton
     */
    protected $buttonMock;

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->buttonMock = $this->objectManager->get(SaveAndContinueButton::class);
    }

    /**
     * @covers \Dem\HelpDesk\Block\Adminhtml\Department\Buttons\SaveAndContinueButton::getButtonData()
     */
    public function testGetButtonData()
    {
        $this->assertIsArray($this->buttonMock->getButtonData());
    }
}
