<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Helper;

use Dem\HelpDesk\Helper\Config;
use Dem\HelpDesk\Helper\Data;
use Dem\HelpDesk\Model\Department;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\App\Config as ScopeConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\State;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Website;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\App\Area;

/**
 * HelpDesk Helper - Config
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class HelperDataTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var Data
     */
    protected $dataHelper;

    /**
     * @var MockObject|Data
     */
    protected $dataHelperMock;

    /**
     * @var MockObject|Website
     */
    protected $website;

    /**
     * @var MockObject|ScopeConfig
     */
    protected $scopeConfigMock;

    /**
     * @var MockObject|Department
     */
    protected $department;

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->dataHelper = $this->objectManager->get(Data::class);
        $this->dataHelperMock = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getConfiguredEnabledFlag',
                'getIsAdminArea',
                'getStoreManager',
                'getCurrentState',
                'getCurrentArea',
                'getScopeConfig'
            ])
            ->getMock();

        $this->website = $this->objectManager->get(Website::class);

        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfig::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'isSetFlag',
                'getValue',
            ])
            ->getMock();

        $this->dataHelperMock->expects($this->any())
            ->method('getScopeConfig')
            ->willReturn($this->scopeConfigMock);
    }

    /**
     * @covers \Dem\HelpDesk\Helper\Data::isEnabled()
     */
    public function testIsEnabled()
    {
        $this->dataHelperMock->expects($this->once())
            ->method('getIsAdminArea')
            ->will($this->returnValue(true));

        // Test admin website / admin area
        $this->website->setId(Config::HELPDESK_WEBSITE_ID_ADMIN);
        $this->assertTrue($this->dataHelperMock->isEnabled($this->website));

        // Test default website
        $this->website->setId(Config::HELPDESK_WEBSITE_ID_DEFAULT);
        $this->assertFalse($this->dataHelperMock->isEnabled($this->website));

        $this->dataHelperMock->expects($this->once())
            ->method('getConfiguredEnabledFlag')
            ->will($this->returnValue(true));

        // Test not admin or default
        $this->website->setId(99);
        $this->assertTrue($this->dataHelperMock->isEnabled($this->website));
    }

    /**
     * @covers \Dem\HelpDesk\Helper\Data::getIsAdminArea()
     */
    public function testGetIsAdminArea()
    {
        /** @var MockObject|Data $helperMock */
        $helperMock = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getCurrentArea'
            ])
            ->getMock();

        $helperMock->expects($this->once())
            ->method('getCurrentArea')
            ->willReturn(Area::AREA_ADMINHTML);

        $this->assertTrue($helperMock->getIsAdminArea());
    }

    /**
     * @covers \Dem\HelpDesk\Helper\Data::getIsFrontendArea()
     */
    public function testGetIsFrontendArea()
    {
        /** @var MockObject|Data $helperMock */
        $helperMock = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getCurrentArea'
            ])
            ->getMock();

        $helperMock->expects($this->once())
            ->method('getCurrentArea')
            ->willReturn(Area::AREA_FRONTEND);

        $this->assertTrue($helperMock->getIsFrontendArea());
    }

    /**
     * @covers \Dem\HelpDesk\Helper\Data::getConfiguredEnabledFlag()
     */
    public function testGetConfiguredEnabledFlag()
    {
        // Need a different mock instance to exclude
        // the 'getConfiguredEnabledFlag' method
        /** @var MockObject|Data $helperMock */
        $helperMock = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getScopeConfig'
            ])
            ->getMock();

        $helperMock->expects($this->any())
            ->method('getScopeConfig')
            ->willReturn($this->scopeConfigMock);

        $this->scopeConfigMock->expects($this->once())
            ->method('isSetFlag')
            ->will($this->returnValue(true));

        $this->assertTrue($helperMock->getConfiguredEnabledFlag());
    }

    /**
     * @covers \Dem\HelpDesk\Helper\Data::getConfiguredFrontendLabel()
     */
    public function testGetConfiguredFrontendLabel()
    {
        $testFrontendLabel = 'Test Frontend Label';
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($testFrontendLabel);

        $this->assertEquals($testFrontendLabel, $this->dataHelperMock->getConfiguredFrontendLabel());
    }

    /**
     * @covers \Dem\HelpDesk\Helper\Data::getConfiguredSenderEmail()
     */
    public function testGetConfiguredSenderEmail()
    {
        $testSenderEmail = 'test@helpdesk.example.com';
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($testSenderEmail);

        $this->assertEquals($testSenderEmail, $this->dataHelperMock->getConfiguredSenderEmail());
    }

    /**
     * @covers \Dem\HelpDesk\Helper\Data::getConfiguredDepartmentLabel()
     */
    public function testGetConfiguredDepartmentLabel()
    {
        $testDepartmentLabel = 'Test Department';
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($testDepartmentLabel);

        $this->assertEquals($testDepartmentLabel, $this->dataHelperMock->getConfiguredDepartmentLabel());
    }

    /**
     * @covers \Dem\HelpDesk\Helper\Data::getConfiguredNotifyActiveFlag()
     */
    public function testGetConfiguredNotifyActiveFlag()
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('isSetFlag')
            ->willReturn(true);

        $this->assertTrue($this->dataHelperMock->getConfiguredNotifyActiveFlag());
    }

    /**
     * @covers \Dem\HelpDesk\Helper\Data::getConfiguredSessionTimeoutMinutes()
     */
    public function testGetConfiguredSessionTimeoutMinutes()
    {
        $testSessionTimeout = 10;
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($testSessionTimeout);

        $this->assertEquals($testSessionTimeout, $this->dataHelperMock->getConfiguredSessionTimeoutMinutes());
    }

    /**
     * @covers \Dem\HelpDesk\Helper\Data::getConfiguredInactiveIntervalDays()
     */
    public function testGetConfiguredInactiveIntervalDays()
    {
        $testInactiveDays = 10;
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($testInactiveDays);

        $this->assertEquals($testInactiveDays, $this->dataHelperMock->getConfiguredInactiveIntervalDays());
    }

    /**
     * @covers \Dem\HelpDesk\Helper\Data::getConfiguredArchiveIntervalDays()
     */
    public function testGetConfiguredArchiveIntervalDays()
    {
        $testArchiveDays = 10;
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($testArchiveDays);

        $this->assertEquals($testArchiveDays, $this->dataHelperMock->getConfiguredArchiveIntervalDays());
    }

}
