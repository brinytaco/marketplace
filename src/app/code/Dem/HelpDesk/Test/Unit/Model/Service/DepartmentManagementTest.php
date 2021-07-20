<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model\Service;

use Dem\HelpDesk\Model\Department;
use Dem\HelpDesk\Model\Service\DepartmentManagement;
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
class DepartmentManagementTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|DepartmentManagement
     */
    protected $modelManager;

    /**
     *
     * @var MockObject|Department
     */
    protected $department;

    /**
     * Test Department data
     * @var []
     */
    protected $deptData = [
        Department::WEBSITE_ID => 0,
        Department::CASE_MANAGER_ID => 1,
        Department::NAME => 'Test Department Name'
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->modelManager = $this->objectManager->create(DepartmentManagement::class);
        $this->modelManagerMock = $this->getMockBuilder(DepartmentManagement::class)
            ->setMethods([
                'validate',
                'getRequiredFields',
                'getEditableFields',
            ])
            ->getMock();
    }

    /**
     * Covers void return value + 1st Exception block
     *
     * @covers \Dem\HelpDesk\Model\Service\DepartmentManagement::filterEditableData()
     */
    public function testFilterEditableData()
    {
        $deptData = [
            Department::CASE_MANAGER_ID => 1,
            Department::NAME => 'Test Department Name',
            Department::DESCRIPTION => 'Test Department Name',
        ];

        $editableFields = [
            Department::NAME,
            Department::DESCRIPTION,
            Department::IS_ACTIVE,
            Department::IS_INTERNAL,
            Department::SORT_ORDER,
            Department::CASE_MANAGER_ID
        ];

        $testData = $deptData;
        $testData['invalid_key'] = 'invalid_data';

        $this->modelManagerMock->expects($this->once())
            ->method('getEditableFields')
            ->willReturn($editableFields);

        $this->assertSame($deptData, $this->modelManagerMock->filterEditableData($testData));
    }

    /**
     * Covers void return value + 1st Exception block
     *
     * @covers \Dem\HelpDesk\Model\Service\DepartmentManagement::validate()
     */
    public function testValidate1()
    {
        $this->assertNull($this->modelManager->validate($this->deptData));

        $this->deptData[Department::WEBSITE_ID] = '';
        $this->expectExceptionObject(
            new \Dem\HelpDesk\Exception(__('The %1 `%2` cannot be empty', 'department', Department::WEBSITE_ID))
        );
        $this->modelManager->validate($this->deptData);
    }

    /**
     * Covers 2nd Exception block
     *
     * @covers \Dem\HelpDesk\Model\Service\DepartmentManagement::validate()
     */
    public function testValidate2()
    {
        unset($this->deptData[Department::WEBSITE_ID]);
        $this->expectExceptionObject(
            new \Dem\HelpDesk\Exception(__('The %1 `%2` cannot be empty', 'department', Department::WEBSITE_ID))
        );
        $this->modelManager->validate($this->deptData);
    }

    /**
     * Covers return of empty required array
     *
     * @covers \Dem\HelpDesk\Model\Service\DepartmentManagement::validate()
     */
    public function testValidate3()
    {
        /** @var DepartmentManagement|MockObject $modelManagerMock */
        $modelManagerMock = $this->getMockBuilder(DepartmentManagement::class)
            ->setMethods([
                'getRequiredFields'
            ])
            ->getMock();

        $modelManagerMock->expects($this->once())
            ->method('getRequiredFields')
            ->willReturn([]);

        $modelManagerMock->validate($this->deptData);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\DepartmentManagement::getRequiredFields()
     */
    public function testGetRequiredFields()
    {
        $fields = [
            Department::WEBSITE_ID,
            Department::CASE_MANAGER_ID,
            Department::NAME
        ];

        $this->assertSame($fields, $this->modelManager->getRequiredFields());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\DepartmentManagement::getEditableFields()
     */
    public function testGetEditableFields()
    {
        $fields = [
            Department::NAME,
            Department::DESCRIPTION,
            Department::IS_ACTIVE,
            Department::IS_INTERNAL,
            Department::SORT_ORDER,
            Department::CASE_MANAGER_ID
        ];

        $this->assertSame($fields, $this->modelManager->getEditableFields());
    }
}
