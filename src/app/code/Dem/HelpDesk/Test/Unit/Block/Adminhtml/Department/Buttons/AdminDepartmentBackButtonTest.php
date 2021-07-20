<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Block\Adminhtml\Department\Buttons;

use Dem\HelpDesk\Block\Adminhtml\Department\Buttons\BackButton;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Url;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Block Adminhtml Department BackButton
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class AdminDepartmentBackButtonTest extends TestCase
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

    /**
     * URL builder
     *
     * @var Url
     */
    protected $urlBuilderMock;


    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->buttonMock = $this->getMockBuilder(BackButton::class)
            ->disableOriginalConstructor()
            ->setMethods(['getButtonUrl'])
            ->getMock();

        $this->urlBuilderMock = $this->getMockBuilder(Url::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUrl'])
            ->getMock();

    }

    /**
     * @covers \Dem\HelpDesk\Block\Adminhtml\Department\Buttons\BackButton::getButtonData()
     */
    public function testGetButtonData()
    {
        $testUrl = 'helpdesk/department/back';

        $this->buttonMock->expects($this->once())
            ->method('getButtonUrl')
            ->willReturn($testUrl);

        $this->assertIsArray($this->buttonMock->getButtonData());
    }
}
