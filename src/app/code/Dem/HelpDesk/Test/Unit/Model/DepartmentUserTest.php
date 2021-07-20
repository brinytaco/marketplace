<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model;

use Dem\HelpDesk\Model\DepartmentUser;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Registry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Model DepartmentUser
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class DepartmentUserTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|Registry
     */
    protected $_registry;

    /**
     * @var MockObject|DepartmentUser
     */
    protected $departmentUser;

    /**
     * Test Department data
     * @var []
     */
    protected $deptUserData = [
        DepartmentUser::DEPT_USER_ID => 1,
        DepartmentUser::DEPARTMENT_ID => 1,
        DepartmentUser::USER_ID => 1,
        DepartmentUser::IS_FOLLOWER => 1,
        DepartmentUser::CREATED_AT => '2021-05-24 16:34:38',
        DepartmentUser::UPDATED_AT => '2021-05-24 16:34:38'
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->registry = $this->objectManager->create(\Magento\Framework\Registry::class);
        $this->departmentUser = $this->objectManager->create(DepartmentUser::class);

        $this->departmentUser->setData($this->deptUserData);
    }

    /**
     * @covers \Dem\HelpDesk\Model\DepartmentUser::getId()
     */
    public function testGetId()
    {
        $this->assertEquals($this->deptUserData[DepartmentUser::DEPT_USER_ID], $this->departmentUser->getId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\DepartmentUser::setId()
     */
    public function testSetId()
    {
        $testId = 99;
        $this->departmentUser->setId($testId);
        $this->assertEquals($testId, $this->departmentUser->getData(DepartmentUser::DEPT_USER_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\DepartmentUser::getDepartmentId()
     */
    public function testGetDepartmentId()
    {
        $this->assertEquals($this->deptUserData[DepartmentUser::DEPARTMENT_ID], $this->departmentUser->getDepartmentId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\DepartmentUser::setDepartmentId()
     */
    public function testSetDepartmentId()
    {
        $testDepartmentId = 99;
        $this->departmentUser->setDepartmentId($testDepartmentId);
        $this->assertEquals($testDepartmentId, $this->departmentUser->getData(DepartmentUser::DEPARTMENT_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\DepartmentUser::getUserId()
     */
    public function testGetUserId()
    {
        $this->assertEquals($this->deptUserData[DepartmentUser::USER_ID], $this->departmentUser->getUserId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\DepartmentUser::setUserId()
     */
    public function testSetUserId()
    {
        $testUserId = 99;
        $this->departmentUser->setUserId($testUserId);
        $this->assertEquals($testUserId, $this->departmentUser->getData(DepartmentUser::USER_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\DepartmentUser::getIsFollower()
     */
    public function testGetIsFollower()
    {
        $this->assertEquals($this->deptUserData[DepartmentUser::IS_FOLLOWER], $this->departmentUser->getIsFollower());
    }

    /**
     * @covers \Dem\HelpDesk\Model\DepartmentUser::setIsFollower()
     */
    public function testSetIsFollower()
    {
        $testFollower = 0;
        $this->departmentUser->setIsFollower($testFollower);
        $this->assertEquals($testFollower, $this->departmentUser->getData(DepartmentUser::IS_FOLLOWER));
    }

    /**
     * @covers \Dem\HelpDesk\Model\DepartmentUser::getCreatedAt()
     */
    public function testGetCreatedAt()
    {
        $this->assertEquals($this->deptUserData[DepartmentUser::CREATED_AT], $this->departmentUser->getCreatedAt());
    }

    /**
     * @covers \Dem\HelpDesk\Model\DepartmentUser::setCreatedAt()
     */
    public function testSetCreatedAt()
    {
        $createdAt = date('Y-m-d h:m:s');
        $this->departmentUser->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $this->departmentUser->getData(DepartmentUser::CREATED_AT));
    }

    /**
     * @covers \Dem\HelpDesk\Model\DepartmentUser::getUpdatedAt()
     */
    public function testGetUpdatedAt()
    {
        $this->assertEquals($this->deptUserData[DepartmentUser::UPDATED_AT], $this->departmentUser->getUpdatedAt());
    }

    /**
     * @covers \Dem\HelpDesk\Model\DepartmentUser::setUpdatedAt()
     */
    public function testSetUpdatedAt()
    {
        $updatedAt = date('Y-m-d h:m:s');
        $this->departmentUser->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $this->departmentUser->getData(DepartmentUser::UPDATED_AT));
    }
}
