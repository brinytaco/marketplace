<?php

declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model\Source\CaseItem;

use Dem\HelpDesk\Model\Source\CaseItem\Website;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Source Model Options
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class SourceCaseItemWebsiteTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|Website
     */
    protected $sourceOptions;

    /**
     * @var MockObject|\Dem\HelpDesk\Helper\Data
     */
    protected $helper;

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();
        $this->sourceOptions = $this->getMockBuilder(Website::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getShouldAddEmptyOption',
                'getStore',
                'getHelper',
                'changeAdminWebsiteName',
                'filterDefaultWebsite',
                'filterDisabledWebsites',
                'getRequest'
            ])
            ->getMock();

        $this->helper = $this->getMockBuilder(\Dem\HelpDesk\Helper\Data::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getIsAdminArea',
                'getWebsite',
                'isEnabled'
            ])
            ->getMock();

        $this->request = $this->getMockBuilder(\Magento\Framework\App\Request\Http::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getControllerName',
                'getActionName'
            ])
            ->getMock();
    }

    /**
     * @covers \Dem\HelpDesk\Model\Source\CaseItem\Website::toOptionArray()
     */
    public function testGetToOptionArray()
    {
        $store = $this->objectManager->get(\Magento\Store\Model\System\Store::class);

        $this->sourceOptions->expects($this->once())
            ->method('getShouldAddEmptyOption')
            ->willReturn(true);

        $this->sourceOptions->expects($this->once())
            ->method('getHelper')
            ->willReturn($this->helper);

        $this->helper->expects($this->once())
            ->method('getIsAdminArea')
            ->willReturn(false);

        $this->sourceOptions->expects($this->once())
            ->method('getStore')
            ->willReturn($store);

        $this->assertIsArray($this->sourceOptions->toOptionArray());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Source\CaseItem\Website::getShouldAddEmptyOption()
     */
    public function testGetShouldAddEmptyOption()
    {
        // Reset so we can test this method
        /** @var MockObject|Website $sourceOptions */
        $sourceOptions = $this->getMockBuilder(Website::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getRequest'
            ])
            ->getMock();

        $sourceOptions->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->request);

        $this->assertFalse($sourceOptions->getShouldAddEmptyOption());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Source\CaseItem\Website::changeAdminWebsiteName()
     */
    public function testChangeAdminWebsiteName()
    {
        /** @var MockObject|Website $sourceOptions */
        $sourceOptions = $this->objectManager->get(Website::class);

        $options = [
            ['label' => Website::getEmptySelectOptionText(), 'value' => ''],
            ['label' => 'Admin', 'value' => '0']
        ];

        $this->assertIsArray($sourceOptions->changeAdminWebsiteName($options));
        $this->assertEquals(__('DE INTERNAL'), $options[1]['label']);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Source\CaseItem\Website::filterDefaultWebsite()
     */
    public function testFilterDefaultWebsite()
    {
        /** @var MockObject|Website $sourceOptions */
        $sourceOptions = $this->objectManager->get(Website::class);

        $options = [
            ['label' => Website::getEmptySelectOptionText(), 'value' => ''],
            ['label' => 'Default Website', 'value' => '1']
        ];
        $this->assertIsArray($sourceOptions->filterDefaultWebsite($options));
        $this->assertArrayNotHasKey('1', $options);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Source\CaseItem\Website::filterDisabledWebsites()
     */
    public function testFilterDisabledWebsites()
    {
        /** @var MockObject|Website $sourceOptions */
        $sourceOptions = $this->getMockBuilder(Website::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getHelper'
            ])
            ->getMock();

        $sourceOptions->expects($this->any())
            ->method('getHelper')
            ->willReturn($this->helper);

        $this->helper->expects($this->once())
            ->method('isEnabled')
            ->willReturn(false);

        $options = [
            ['label' => Website::getEmptySelectOptionText(), 'value' => ''],
            ['label' => 'Admin', 'value' => '99']
        ];
        $this->assertIsArray($sourceOptions->filterDisabledWebsites($options));
        $this->assertArrayNotHasKey('1', $options);
    }
}
