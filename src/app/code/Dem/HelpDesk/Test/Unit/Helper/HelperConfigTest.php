<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Helper;

use Dem\HelpDesk\Helper\Config;
use Dem\HelpDesk\Model\Department;
use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\Website;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

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
class HelperConfigTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|Config
     */
    protected $configHelper;

    /**
     * @var MockObject|Website
     */
    protected $website;

    /**
     * @var MockObject|Department
     */
    protected $department;

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->configHelper = $this->objectManager->get(Config::class);

        $this->website = $this->objectManager->get(Website::class);
        $this->department = $this->objectManager->get(Department::class);
    }

    /**
     * @covers \Dem\HelpDesk\Helper\Config::isDefaultWebsite()
     */
    public function testIsDefaultWebsite()
    {
        $this->website->setData('id', Config::HELPDESK_WEBSITE_ID_ADMIN);
        $this->assertFalse($this->configHelper->isDefaultWebsite($this->website));
        $this->website->setData('id', Config::HELPDESK_WEBSITE_ID_DEFAULT);
        $this->assertTrue($this->configHelper->isDefaultWebsite($this->website));
    }

    /**
     * @covers \Dem\HelpDesk\Helper\Config::isAdminWebsite()
     */
    public function testIsAdminWebsite()
    {
        $this->website->setData('id', Config::HELPDESK_WEBSITE_ID_DEFAULT);
        $this->assertFalse($this->configHelper->isAdminWebsite($this->website));
        $this->website->setData('id', Config::HELPDESK_WEBSITE_ID_ADMIN);
        $this->assertTrue($this->configHelper->isAdminWebsite($this->website));
    }

    /**
     * @covers \Dem\HelpDesk\Helper\Config::isDefaultDepartment()
     */
    public function testIsDefaultDepartment()
    {
        $this->department->setData('department_id', 99);
        $this->assertFalse($this->configHelper->isDefaultDepartment($this->department));
        $this->department->setData('department_id', Config::HELPDESK_DEPARTMENT_DEFAULT_ID);
        $this->assertTrue($this->configHelper->isDefaultDepartment($this->department));
    }
}
