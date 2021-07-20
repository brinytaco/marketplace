<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Block\Adminhtml\Department\Buttons;

use Dem\HelpDesk\Block\Adminhtml\Department\Buttons\SaveButton;
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
class AdminDepartmentSaveButtonTest extends TestCase
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

        $this->buttonMock = $this->objectManager->get(SaveButton::class);
    }

    /**
     * @covers \Dem\HelpDesk\Block\Adminhtml\Department\Buttons\SaveButton::getButtonData()
     */
    public function testGetButtonData()
    {
        $this->assertIsArray($this->buttonMock->getButtonData());
    }
}
