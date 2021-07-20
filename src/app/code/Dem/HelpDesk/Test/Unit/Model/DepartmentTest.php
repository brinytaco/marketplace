<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model;

use Dem\HelpDesk\Model\Department;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Registry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Model Department
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class DepartmentTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|Registry
     */
    protected $registry;

    /**
     * @var MockObject|Department
     */
    protected $department;

    /**
     * Test Department data
     * @var []
     */
    protected $deptData = [
        Department::DEPARTMENT_ID => 1,
        Department::WEBSITE_ID => 1,
        Department::CASE_MANAGER_ID => 1,
        Department::NAME => 'Default Department',
        Department::DESCRIPTION => null,
        Department::IS_INTERNAL => 0,
        Department::IS_ACTIVE => 1,
        Department::SORT_ORDER => 1,
        Department::CREATED_AT => '2021-05-24 16:34:38',
        Department::UPDATED_AT => '2021-05-24 16:34:38',
        Department::CASE_MANAGER_NAME => 'Jack Sprat',
        Department::CASE_MANAGER_EMAIL => 'jack@sprat.com'
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->registry = $this->objectManager->create(\Magento\Framework\Registry::class);
        $this->department = $this->objectManager->create(Department::class);

        $this->department->setData($this->deptData);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::getId()
     */
    public function testGetId()
    {
        $this->assertEquals($this->deptData[Department::DEPARTMENT_ID], $this->department->getId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::setId()
     */
    public function testSetId()
    {
        $testId = 99;
        $this->department->setId($testId);
        $this->assertEquals($testId, $this->department->getData(Department::DEPARTMENT_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::getWebsiteId()
     */
    public function testGetWebsiteId()
    {
        $this->assertEquals($this->deptData[Department::WEBSITE_ID], $this->department->getWebsiteId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::setWebsiteId()
     */
    public function testSetWebsiteId()
    {
        $testWebsiteId = 99;
        $this->department->setWebsiteId($testWebsiteId);
        $this->assertEquals($testWebsiteId, $this->department->getData(Department::WEBSITE_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::getCaseManagerId()
     */
    public function testGetCaseManagerId()
    {
        $this->assertEquals($this->deptData[Department::CASE_MANAGER_ID], $this->department->getCaseManagerId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::setCaseManagerId()
     */
    public function testSetCaseManagerId()
    {
        $testCaseManagerId = 99;
        $this->department->setCaseManagerId($testCaseManagerId);
        $this->assertEquals($testCaseManagerId, $this->department->getData(Department::CASE_MANAGER_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::getName()
     */
    public function testGetName()
    {
        $this->assertEquals($this->deptData[Department::NAME], $this->department->getName());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::setName()
     */
    public function testSetName()
    {
        $testDeptName = 'New Department';
        $this->department->setName($testDeptName);
        $this->assertEquals($testDeptName, $this->department->getData(Department::NAME));
    }


    /**
     * @covers \Dem\HelpDesk\Model\Department::getDescription()
     */
    public function testGetDescription()
    {
        $this->assertEquals($this->deptData[Department::DESCRIPTION], $this->department->getDescription());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::setDescription()
     */
    public function testSetDescription()
    {
        $testDescription = 'A Simple Description';
        $this->department->setDescription($testDescription);
        $this->assertEquals($testDescription, $this->department->getData(Department::DESCRIPTION));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::getIsInternal()
     */
    public function testGetIsInternal()
    {
        $this->assertEquals($this->deptData[Department::IS_INTERNAL], $this->department->getIsInternal());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::setIsInternal()
     */
    public function testSetIsInternal()
    {
        $testInternal = 1;
        $this->department->setIsInternal($testInternal);
        $this->assertEquals($testInternal, $this->department->getData(Department::IS_INTERNAL));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::getIsActive()
     */
    public function testGetIsActive()
    {
        $this->assertEquals($this->deptData[Department::IS_ACTIVE], $this->department->getIsActive());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::setIsActive()
     */
    public function testSetIsActive()
    {
        $testActive = 0;
        $this->department->setIsActive($testActive);
        $this->assertEquals($testActive, $this->department->getData(Department::IS_ACTIVE));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::getSortOrder()
     */
    public function testGetSortOrder()
    {
        $this->assertEquals($this->deptData[Department::SORT_ORDER], $this->department->getSortOrder());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::setSortOrder()
     */
    public function testSetSortOrder()
    {
        $testSortOrder = 99;
        $this->department->setSortOrder($testSortOrder);
        $this->assertEquals($testSortOrder, $this->department->getData(Department::SORT_ORDER));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::getCreatedAt()
     */
    public function testGetCreatedAt()
    {
        $this->assertEquals($this->deptData[Department::CREATED_AT], $this->department->getCreatedAt());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::setCreatedAt()
     */
    public function testSetCreatedAt()
    {
        $createdAt = date('Y-m-d h:m:s');
        $this->department->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $this->department->getData(Department::CREATED_AT));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::getUpdatedAt()
     */
    public function testGetUpdatedAt()
    {
        $this->assertEquals($this->deptData[Department::UPDATED_AT], $this->department->getUpdatedAt());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::setUpdatedAt()
     */
    public function testSetUpdatedAt()
    {
        $updatedAt = date('Y-m-d h:m:s');
        $this->department->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $this->department->getData(Department::UPDATED_AT));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::setDefaultFollowers()
     */
    public function testSetDefaultFollowers()
    {
        $testDefaultFollowers = [1,2,3,4,5,6];
        $this->department->setDefaultFollowers($testDefaultFollowers);
        $this->assertNotEquals($testDefaultFollowers, $this->department->getData(Department::DEFAULT_FOLLOWERS));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::getCaseManagerName()
     */
    public function testGetCaseManagerName()
    {
        /** @var MockObject|Department $department */
        $department = $this->getMockBuilder(\Dem\HelpDesk\Model\Department::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getResource'])
            ->getMock();

        $department->setData($this->deptData);
        $department->unsetData(Department::CASE_MANAGER_NAME);

        $resource = $this->getMockBuilder(\Dem\HelpDesk\Model\ResourceModel\Department::class)
            ->disableOriginalConstructor()
            ->setMethods(['setCaseManagerData'])
            ->getMock();

        $department->expects($this->once())
            ->method('getResource')
            ->willReturn($resource);

        $resource->expects($this->once())
            ->method('setCaseManagerData')
            ->willReturn($department);

        $this->assertNull($department->getCaseManagerName());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::setCaseManagerName()
     */
    public function testSetCaseManagerName()
    {
        $testCMName = 'John Smith';
        $this->department->setCaseManagerName($testCMName);
        $this->assertNotEquals($testCMName, $this->department->getData(Department::CASE_MANAGER_NAME));
    }
    /**
     * @covers \Dem\HelpDesk\Model\Department::getCaseManagerEmail()
     */
    public function testGetCaseManagerEmail()
    {
        $this->assertEquals($this->deptData[Department::CASE_MANAGER_EMAIL], $this->department->getCaseManagerEmail());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Department::setCaseManagerEmail()
     */
    public function testSetCaseManagerEmail()
    {
        $testCMEmail = 'john@example.com';
        $this->department->setCaseManagerEmail($testCMEmail);
        $this->assertNotEquals($testCMEmail, $this->department->getData(Department::CASE_MANAGER_EMAIL));
    }

}
