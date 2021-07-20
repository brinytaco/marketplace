<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model\Service;

use Dem\HelpDesk\Model\CaseItem;
use Dem\HelpDesk\Model\DepartmentRepository;
use Dem\HelpDesk\Model\Department;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\Service\CaseItemManagement;
use Magento\Customer\Model\Customer;
use Magento\Framework\App\ObjectManager;
use Magento\User\Model\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Model Service CaseItemManagement
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class CaseItemManagementTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|\Dem\HelpDesk\Model\Service\CaseItemManagement
     */
    protected $modelManager;

    /**
     * @var MockObject|\Dem\HelpDesk\Model\Service\CaseItemManagement
     */
    protected $modelManagerMock;

    /**
     *
     * @var CaseItem
     */
    protected $caseItem;

    /**
     * @var MockObject|\Dem\HelpDesk\Model\DepartmentRepository
     */
    protected $departmentRepository;

    /**
     * Test Case data
     * @var []
     */
    protected $caseData = [
        CaseItem::WEBSITE_ID => 0,
        CaseItem::DEPARTMENT_ID => 1,
        CaseItem::SUBJECT => 'the time is now again',
        CaseItem::PRIORITY => 0,
        Reply::REPLY_TEXT => 'Now is the time for all good men to come to the aid of their country'
    ];

    protected $creatorData = [
        'id' => 1,
        'firstname' => 'Jack',
        'lastname' => 'Sprat',
        'email' => 'jack@sprat.com'
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->modelManager = $this->objectManager->create(CaseItemManagement::class);
        $this->modelManagerMock = $this->getMockBuilder(CaseItemManagement::class)
            ->setMethods([
                'validate',
                'validateHelpDeskWebsiteById',
                'validateDepartmentByWebsiteId',
                'getIsCreatorTypeAdmin',
                'getRequiredFields',
                'getDepartmentRepository',
                'getDepartmentById'
            ])
            ->getMock();

        $this->departmentRepository = $this->getMockBuilder(DepartmentRepository::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getById'
            ])
            ->getMock();
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\CaseItemManagement::createCase()
     */
    public function testCreateAdminCase()
    {
        /** @var CaseItem|MockObject $caseItem */
        /** @var User|MockObject $creator */
        $caseItem = $this->objectManager->create(CaseItem::class);
        $creator = $this->objectManager->create(User::class);
        $creator->addData($this->creatorData);

        $this->modelManagerMock->expects($this->once())
            ->method('validateHelpDeskWebsiteById')
            ->will($this->returnSelf());

        $this->modelManagerMock->expects($this->once())
            ->method('validateHelpDeskWebsiteById')
            ->will($this->returnSelf());

        $this->modelManagerMock->expects($this->once())
            ->method('validateDepartmentByWebsiteId')
            ->will($this->returnSelf());

        $this->modelManagerMock->expects($this->exactly(2))
            ->method('getIsCreatorTypeAdmin')
            ->will($this->returnValue(true));

        $newCase = $this->modelManagerMock->createCase($caseItem, $creator, $this->caseData);
        $this->assertInstanceOf(CaseItem::class, $newCase);
        $this->assertEquals($creator->getId(), $newCase->getCreatorAdminId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\CaseItemManagement::createCase()
     */
    public function testCreateCustomerCase()
    {
        /** @var CaseItem|MockObject $caseItem */
        /** @var User|MockObject $creator */
        $caseItem = $this->objectManager->create(CaseItem::class);
        $creator = $this->objectManager->create(User::class);
        $creator->addData($this->creatorData);

        $this->modelManagerMock->expects($this->once())
            ->method('validateHelpDeskWebsiteById')
            ->will($this->returnSelf());

        $this->modelManagerMock->expects($this->once())
            ->method('validateDepartmentByWebsiteId')
            ->will($this->returnSelf());

        $this->modelManagerMock->expects($this->exactly(2))
            ->method('getIsCreatorTypeAdmin')
            ->will($this->returnValue(false));

        $newCase = $this->modelManagerMock->createCase($caseItem, $creator, $this->caseData);
        $this->assertInstanceOf(CaseItem::class, $newCase);
        $this->assertEquals($creator->getId(), $newCase->getCreatorCustomerId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\CaseItemManagement::getIsCreatorTypeAdmin()
     */
    public function testGetIsCreatorTypeAdmin()
    {
        $this->modelManager = $this->objectManager->create(CaseItemManagement::class);

        $creatorUser = $this->objectManager->create(User::class);
        $this->assertTrue($this->modelManager->getIsCreatorTypeAdmin($creatorUser));

        $creatorCustomer = $this->objectManager->create(Customer::class);
        $this->assertFalse($this->modelManager->getIsCreatorTypeAdmin($creatorCustomer));
    }

    /**
     * Covers void return value + 2nd Exception block
     *
     * @covers \Dem\HelpDesk\Model\Service\CaseItemManagement::validate()
     */
    public function testValidate1()
    {
        $this->assertNull($this->modelManager->validate($this->caseData));

        $this->caseData[CaseItem::WEBSITE_ID] = '';
        $this->expectExceptionObject(
            new \Dem\HelpDesk\Exception(__('The %1 `%2` cannot be empty', 'case', CaseItem::WEBSITE_ID))
        );
        $this->modelManager->validate($this->caseData);
    }

    /**
     * Covers 1st Exception block
     *
     * @covers \Dem\HelpDesk\Model\Service\CaseItemManagement::validate()
     */
    public function testValidate2()
    {
        unset($this->caseData[CaseItem::WEBSITE_ID]);
        $this->expectExceptionObject(
            new \Dem\HelpDesk\Exception(__('The %1 `%2` cannot be empty', 'case', CaseItem::WEBSITE_ID))
        );
        $this->modelManager->validate($this->caseData);

        $this->modelManager->expects($this->once())
            ->method('getRequiredFields')
            ->willReturn([]);

        $this->modelManager->validate($this->caseData);
    }

    /**
     * Covers return of empty required array
     *
     * @covers \Dem\HelpDesk\Model\Service\CaseItemManagement::validate()
     */
    public function testValidate3()
    {
        /** @var CaseItemManagement|MockObject $modelManagerMock */
        $modelManagerMock = $this->getMockBuilder(CaseItemManagement::class)
            ->setMethods([
                'getRequiredFields'
            ])
            ->getMock();

        $modelManagerMock->expects($this->once())
            ->method('getRequiredFields')
            ->willReturn([]);

        $modelManagerMock->validate($this->caseData);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\CaseItemManagement::getRequiredFields()
     */
    public function testGetRequiredFields()
    {
        $fields = [
            CaseItem::WEBSITE_ID,
            CaseItem::DEPARTMENT_ID,
            CaseItem::SUBJECT,
            CaseItem::PRIORITY,
            Reply::REPLY_TEXT
        ];

        $this->assertSame($fields, $this->modelManager->getRequiredFields());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\CaseItemManagement::getEditableFields()
     */
    public function testGetEditableFields()
    {
        $fields = [];

        $this->assertSame($fields, $this->modelManager->getEditableFields());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\CaseItemManagement::getDepartmentById()
     */
    public function testGetDepartmentByIdSuccess()
    {
        $department = $this->objectManager->create(Department::class);
        $department->setId(1);

        /** @var CaseItemManagement|MockObject $modelManagerMock */
        $modelManagerMock = $this->getMockBuilder(CaseItemManagement::class)
            ->setMethods([
                'getDepartmentRepository',
            ])
            ->getMock();

        $modelManagerMock->expects($this->once())
            ->method('getDepartmentRepository')
            ->willReturn($this->departmentRepository);

        $this->departmentRepository->expects($this->once())
            ->method('getById')
            ->willReturn($department);

        $this->assertInstanceOf(Department::class, $modelManagerMock->getDepartmentById(1));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\CaseItemManagement::getDepartmentById()
     */
    public function testGetDepartmentByIdFail()
    {
        $department = $this->objectManager->create(Department::class);

        /** @var CaseItemManagement|MockObject $modelManagerMock */
        $modelManagerMock = $this->getMockBuilder(CaseItemManagement::class)
            ->setMethods([
                'getDepartmentRepository',
            ])
            ->getMock();

        $modelManagerMock->expects($this->once())
            ->method('getDepartmentRepository')
            ->willReturn($this->departmentRepository);

        $this->departmentRepository->expects($this->once())
            ->method('getById')
            ->willReturn($department);

        $this->expectExceptionObject(
            new \Dem\HelpDesk\Exception(__('Invalid department selected'))
        );
        $modelManagerMock->getDepartmentById(1);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\CaseItemManagement::validateHelpDeskWebsiteById()
     */
    public function testValidateHelpDeskWebsiteByIdFail()
    {
        $websiteId = 99;
        $case = $this->objectManager->create(CaseItem::class);
        $case->setData(CaseItem::WEBSITE_ID, $websiteId);

        /** @var CaseItemManagement|MockObject $modelManagerMock */
        $modelManagerMock = $this->getMockBuilder(CaseItemManagement::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getHelper',
            ])
            ->getMock();
        /** @var \Dem\HelpDesk\Helper\Data|MockObject $helper */
        $helper = $this->getMockBuilder(\Dem\HelpDesk\Helper\Data::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'isEnabled',
            ])
            ->getMock();

        $modelManagerMock->expects($this->once())
            ->method('getHelper')
            ->willReturn($helper);

        $helper->expects($this->once())
            ->method('isEnabled')
            ->willReturn(false);

        $this->expectExceptionObject(
            new \Dem\HelpDesk\Exception(__('Invalid website selected: %1', $websiteId))
        );

        $modelManagerMock->validateHelpDeskWebsiteById($case);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\CaseItemManagement::validateHelpDeskWebsiteById()
     */
    public function testValidateHelpDeskWebsiteByIdSuccess()
    {
        $websiteId = 99;
        $case = $this->objectManager->create(CaseItem::class);
        $case->setData(CaseItem::WEBSITE_ID, $websiteId);

        /** @var CaseItemManagement|MockObject $modelManagerMock */
        $modelManagerMock = $this->getMockBuilder(CaseItemManagement::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getHelper',
            ])
            ->getMock();
        /** @var \Dem\HelpDesk\Helper\Data|MockObject $helper */
        $helper = $this->getMockBuilder(\Dem\HelpDesk\Helper\Data::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'isEnabled',
            ])
            ->getMock();

        $modelManagerMock->expects($this->once())
            ->method('getHelper')
            ->willReturn($helper);

        $helper->expects($this->once())
            ->method('isEnabled')
            ->willReturn(true);

        $this->assertInstanceOf(CaseItemManagement::class, $modelManagerMock->validateHelpDeskWebsiteById($case));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\CaseItemManagement::validateDepartmentByWebsiteId()
     */
    public function testValidateDepartmentByWebsiteIdFail1()
    {
        $deptId = 99;
        $case = $this->objectManager->create(CaseItem::class);
        $case->setData(CaseItem::DEPARTMENT_ID, $deptId);
        $department = $this->objectManager->create(Department::class);
        // $department->setData(CaseItem::DEPARTMENT_ID, $deptId);

        /** @var CaseItemManagement|MockObject $modelManagerMock */
        $modelManagerMock = $this->getMockBuilder(CaseItemManagement::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getDepartmentById',
            ])
            ->getMock();

        $modelManagerMock->expects($this->once())
            ->method('getDepartmentById')
            ->willReturn($department);

        $this->expectExceptionObject(
            new \Dem\HelpDesk\Exception(__('Invalid department selected'))
        );

        $modelManagerMock->validateDepartmentByWebsiteId($case);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\CaseItemManagement::validateDepartmentByWebsiteId()
     */
    public function testValidateDepartmentByWebsiteIdFail2()
    {
        $deptId = 99;
        $case = $this->objectManager->create(CaseItem::class);
        $case->setData(CaseItem::WEBSITE_ID, 0);
        $department = $this->objectManager->create(Department::class);
        $department->setData([
            Department::DEPARTMENT_ID => 55,
            Department::WEBSITE_ID => 55
        ]);

        /** @var CaseItemManagement|MockObject $modelManagerMock */
        $modelManagerMock = $this->getMockBuilder(CaseItemManagement::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getDepartmentById',
            ])
            ->getMock();

        $modelManagerMock->expects($this->once())
            ->method('getDepartmentById')
            ->willReturn($department);

        $this->expectExceptionObject(
            new \Dem\HelpDesk\Exception(__('Invalid department selected'))
        );

        $modelManagerMock->validateDepartmentByWebsiteId($case);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\CaseItemManagement::validateDepartmentByWebsiteId()
     */
    public function testValidateDepartmentByWebsiteIdSuccess()
    {
        $case = $this->objectManager->create(CaseItem::class);
        $case->setData(CaseItem::WEBSITE_ID, 0);
        $department = $this->objectManager->create(Department::class);
        $department->setData([
            Department::DEPARTMENT_ID => 55,
            Department::WEBSITE_ID => 0
        ]);

        /** @var CaseItemManagement|MockObject $modelManagerMock */
        $modelManagerMock = $this->getMockBuilder(CaseItemManagement::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getDepartmentById',
            ])
            ->getMock();

        $modelManagerMock->expects($this->once())
            ->method('getDepartmentById')
            ->willReturn($department);

        $this->assertInstanceOf(CaseItemManagement::class, $modelManagerMock->validateDepartmentByWebsiteId($case));
    }
}
