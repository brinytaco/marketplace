<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model\Service;

use Dem\HelpDesk\Model\DepartmentUser;
use Dem\HelpDesk\Model\Service\DepartmentUserManagement;
use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Model Service DepartmentManagement
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class DepartmentUserManagementTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|DepartmentUserManagement
     */
    protected $modelManager;

    /**
     *
     * @var MockObject|DepartmentUser
     */
    protected $departmentUser;

    /**
     * Test DepartmentUser data
     * @var []
     */
    protected $deptUserData = [
        DepartmentUser::DEPARTMENT_ID => 1,
        DepartmentUser::USER_ID => 1,
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->modelManager = $this->objectManager->create(DepartmentUserManagement::class);
    }

    /**
     * Covers void return value + 1st Exception block
     *
     * @covers \Dem\HelpDesk\Model\Service\DepartmentUserManagement::validate()
     */
    public function testValidate1()
    {
        $this->assertNull($this->modelManager->validate($this->deptUserData));

        $this->deptUserData[DepartmentUser::DEPARTMENT_ID] = '';
        $this->expectExceptionObject(
            new \Dem\HelpDesk\Exception(__('The %1 `%2` cannot be empty', 'department user', DepartmentUser::DEPARTMENT_ID))
        );
        $this->modelManager->validate($this->deptUserData);
    }

    /**
     * Covers 2nd Exception block
     *
     * @covers \Dem\HelpDesk\Model\Service\DepartmentUserManagement::validate()
     */
    public function testValidate2()
    {
        unset($this->deptUserData[DepartmentUser::DEPARTMENT_ID]);
        $this->expectExceptionObject(
            new \Dem\HelpDesk\Exception(__('The %1 `%2` cannot be empty', 'department user', DepartmentUser::DEPARTMENT_ID))
        );
        $this->modelManager->validate($this->deptUserData);
    }

    /**
     * Covers return of empty required array
     *
     * @covers \Dem\HelpDesk\Model\Service\DepartmentUserManagement::validate()
     */
    public function testValidate3()
    {
        /** @var DepartmentUserManagement|MockObject $modelManagerMock */
        $modelManagerMock = $this->getMockBuilder(DepartmentUserManagement::class)
            ->setMethods([
                'getRequiredFields'
            ])
            ->getMock();

        $modelManagerMock->expects($this->once())
            ->method('getRequiredFields')
            ->willReturn([]);

        $modelManagerMock->validate($this->deptUserData);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\DepartmentUserManagement::getRequiredFields()
     */
    public function testGetRequiredFields()
    {
        $fields = [
            DepartmentUser::DEPARTMENT_ID,
            DepartmentUser::USER_ID
        ];

        $this->assertSame($fields, $this->modelManager->getRequiredFields());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\DepartmentUserManagement::getEditableFields()
     */
    public function testGetEditableFields()
    {
        $this->assertSame([], $this->modelManager->getEditableFields());
    }
}
