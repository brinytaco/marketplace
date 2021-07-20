<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model;

use Dem\HelpDesk\Model\CaseItem;
use Dem\HelpDesk\Model\Department;
use Dem\HelpDesk\Model\Follower;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\User;
use Dem\HelpDesk\Model\ResourceModel\CaseItem as Resource;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Model CaseItem
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class CaseItemTest extends TestCase
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
     * @var MockObject|CaseItem
     */
    protected $caseItem;

    /**
     * @var MockObject|Department
     */
    protected $department;

    /**
     * @var MockObject|User
     */
    protected $helpdeskUser;

    /**
     * @var MockObject|Resource
     */
    protected $resourceMock;

    /**
     * Test Case data
     * @var []
     */
    protected $caseData = [
        CaseItem::CASE_ID => 1,
        CaseItem::WEBSITE_ID => 1,
        CaseItem::DEPARTMENT_ID => 1,
        CaseItem::CASE_NUMBER => '001-000001',
        CaseItem::PROTECT_CODE => '78e370a0fb88f375bd3ce4f7f81f4c266fbe5b0d',
        CaseItem::CREATOR_CUSTOMER_ID => 1,
        CaseItem::CREATOR_ADMIN_ID => null,
        CaseItem::CREATOR_NAME => 'Roni Cost',
        CaseItem::CREATOR_EMAIL => 'roni_cost@example.com',
        CaseItem::CREATOR_LAST_READ => null,
        CaseItem::SUBJECT => 'the time is now again',
        CaseItem::STATUS_ID => 70,
        CaseItem::PRIORITY => 0,
        CaseItem::REMOTE_IP => '0.0.0.0',
        CaseItem::HTTP_USER_AGENT => null,
        CaseItem::UPDATER_NAME => 'Super Admin',
        CaseItem::CREATED_AT => '2021-04-07 23:39:53',
        CaseItem::UPDATED_AT => '2021-04-07 23:52:51',
        CaseItem::WEBSITE_NAME => 'DE Internal',
        CaseItem::DEPARTMENT_NAME => 'Default Department',
    ];

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

    protected $userData = [
        User::USER_ID => 1,
        User::NAME => 'Jack Sprat',
        User::EMAIL => 'jack@sprat.com'
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->registry = $this->objectManager->create(\Magento\Framework\Registry::class);

        $this->department = $this->objectManager->create(Department::class);
        $this->department->setData($this->deptData);

        $this->user = $this->objectManager->create(User::class);
        $this->user->setData($this->userData);

        $this->caseItem = $this->objectManager->create(CaseItem::class);
        $this->caseItem->setData($this->caseData);
        $this->caseItem->setData('_department', $this->department);
        $this->caseItem->setData(CaseItem::CASE_MANAGER, $this->user);

        $this->caseMock = $this->getMockBuilder(CaseItem::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getReplies',
                'getResource',
            ])
            ->getMock();

        $this->resourceMock = $this->getMockBuilder(Resource::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'setCaseManager',
                'setCaseNumber',
            ])
            ->getMock();
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getId()
     */
    public function testGetId()
    {
        $this->assertEquals($this->caseData[CaseItem::CASE_ID], $this->caseItem->getId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setId()
     */
    public function testSetId()
    {
        $validId = 99;
        $this->caseItem->setId($validId);
        $this->assertEquals($validId, $this->caseItem->getData(CaseItem::CASE_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getCaseNumber()
     */
    public function testGetCaseNumber()
    {
        $this->assertEquals($this->caseData[CaseItem::CASE_NUMBER], $this->caseItem->getCaseNumber());

        $resource = $this->objectManager->get(Resource::class);
        $this->caseMock->expects($this->once())
            ->method('getResource')
            ->willReturn($this->resourceMock);

        $this->assertNull($this->caseMock->getCaseNumber());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setCaseNumber()
     */
    public function testSetCaseNumber()
    {
        $invalidCaseNumber = '999-123456';
        $this->caseItem->setCaseNumber($invalidCaseNumber);
        $this->assertNotEquals($invalidCaseNumber, $this->caseItem->getData(CaseItem::CASE_NUMBER));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getCaseManager()
     */
    public function testGetCaseManager()
    {
        $this->assertInstanceOf(User::class, $this->caseItem->getCaseManager());

        $this->caseMock->expects($this->once())
            ->method('getResource')
            ->willReturn($this->resourceMock);

        $this->assertNull($this->caseMock->getCaseManager());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setCaseManager()
     */
    public function testSetCaseManager()
    {
        $invalidCaseManager = new DataObject();
        $this->caseItem->setCaseManager($invalidCaseManager);
        $this->assertNotSame($invalidCaseManager, $this->caseItem->getData(CaseItem::CASE_MANAGER));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getWebsiteId()
     */
    public function testGetWebsiteId()
    {
        $this->assertEquals($this->caseData[CaseItem::WEBSITE_ID], $this->caseItem->getWebsiteId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setWebsiteId()
     */
    public function testSetWebsiteId()
    {
        $testWebsiteId = 99;
        $this->caseItem->setWebsiteId($testWebsiteId);
        $this->assertEquals($testWebsiteId, $this->caseItem->getData(CaseItem::WEBSITE_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getDepartmentId()
     */
    public function testGetDepartmentId()
    {
        $this->assertEquals($this->caseData[CaseItem::DEPARTMENT_ID], $this->caseItem->getDepartmentId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setDepartmentId()
     */
    public function testSetDepartmentId()
    {
        $testDepartmentId = 99;
        $this->caseItem->setDepartmentId($testDepartmentId);
        $this->assertEquals($testDepartmentId, $this->caseItem->getData(CaseItem::DEPARTMENT_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getDepartmentName()
     */
    public function testGetDepartmentName()
    {
        $this->assertEquals($this->caseData[CaseItem::DEPARTMENT_NAME], $this->caseItem->getDepartmentName());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setDepartmentName()
     */
    public function testSetDepartmentName()
    {
        $invalidDeptName = 'Bad Department';
        $this->caseItem->setDepartmentName($invalidDeptName);
        $this->assertNotEquals($invalidDeptName, $this->caseItem->getData(CaseItem::DEPARTMENT_NAME));
    }


    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getWebsiteName()
     */
    public function testGetWebsiteName()
    {
        $this->assertEquals($this->caseData[CaseItem::WEBSITE_NAME], $this->caseItem->getWebsiteName());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setWebsiteName()
     */
    public function testSetWebsiteName()
    {
        $invalidWebsiteName = 'Bad Website';
        $this->caseItem->setWebsiteName($invalidWebsiteName);
        $this->assertNotSame($invalidWebsiteName, $this->caseItem->getData(CaseItem::WEBSITE_NAME));
    }


    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getProtectCode()
     */
    public function testGetProtectCode()
    {
        $this->assertEquals($this->caseData[CaseItem::PROTECT_CODE], $this->caseItem->getProtectCode());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setProtectCode()
     */
    public function testSetProtectCode()
    {
        $testProtectCode = '4e23501847f2174e24f393919e4ed3b4de340cbf';
        $this->caseItem->setProtectCode($testProtectCode);
        $this->assertEquals($testProtectCode, $this->caseItem->getData(CaseItem::PROTECT_CODE));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getCreatorCustomerId()
     */
    public function testGetCreatorCustomerId()
    {
        $this->assertEquals($this->caseData[CaseItem::CREATOR_CUSTOMER_ID], $this->caseItem->getCreatorCustomerId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setCreatorCustomerId()
     */
    public function testSetCreatorCustomerId()
    {
        $testCreatorId = '99';
        $this->caseItem->setCreatorCustomerId($testCreatorId);
        $this->assertEquals($testCreatorId, $this->caseItem->getData(CaseItem::CREATOR_CUSTOMER_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getCreatorAdminId()
     */
    public function testGetCreatorAdminId()
    {
        $this->assertEquals($this->caseData[CaseItem::CREATOR_ADMIN_ID], $this->caseItem->getCreatorAdminId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setCreatorAdminId()
     */
    public function testSetCreatorAdminId()
    {
        $testCreatorId = '99';
        $this->caseItem->setCreatorAdminId($testCreatorId);
        $this->assertEquals($testCreatorId, $this->caseItem->getData(CaseItem::CREATOR_ADMIN_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getCreatorName()
     */
    public function testGetCreatorName()
    {
        $this->assertEquals($this->caseData[CaseItem::CREATOR_NAME], $this->caseItem->getCreatorName());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setCreatorName()
     */
    public function testSetCreatorName()
    {
        $testCreatorName = 'John Smith';
        $this->caseItem->setCreatorName($testCreatorName);
        $this->assertEquals($testCreatorName, $this->caseItem->getData(CaseItem::CREATOR_NAME));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getCreatorEmail()
     */
    public function testGetCreatorEmail()
    {
        $this->assertEquals($this->caseData[CaseItem::CREATOR_EMAIL], $this->caseItem->getCreatorEmail());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setCreatorEmail()
     */
    public function testSetCreatorEmail()
    {
        $testCreatorEmail = 'john@smith.com';
        $this->caseItem->setCreatorEmail($testCreatorEmail);
        $this->assertEquals($testCreatorEmail, $this->caseItem->getData(CaseItem::CREATOR_EMAIL));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getCreatorLastRead()
     */
    public function testGetCreatorLastRead()
    {
        $this->assertEquals($this->caseData[CaseItem::CREATOR_LAST_READ], $this->caseItem->getCreatorLastRead());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setCreatorLastRead()
     */
    public function testSetCreatorLastRead()
    {
        $lastId = 99;
        $this->caseItem->setCreatorLastRead($lastId);
        $this->assertEquals($lastId, $this->caseItem->getData(CaseItem::CREATOR_LAST_READ));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getSubject()
     */
    public function testGetSubject()
    {
        $this->assertEquals($this->caseData[CaseItem::SUBJECT], $this->caseItem->getSubject());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setSubject()
     */
    public function testSetSubject()
    {
        $subject = 'Now is the time';
        $this->caseItem->setSubject($subject);
        $this->assertEquals($subject, $this->caseItem->getData(CaseItem::SUBJECT));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getStatusId()
     */
    public function testGetStatusId()
    {
        $this->assertEquals($this->caseData[CaseItem::STATUS_ID], $this->caseItem->getStatusId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setStatusId()
     */
    public function testSetStatusId()
    {
        $statusId = \Dem\HelpDesk\Model\Source\CaseItem\Status::CASE_STATUS_RESOLVED;
        $this->caseItem->setStatusId($statusId);
        $this->assertEquals($statusId, $this->caseItem->getData(CaseItem::STATUS_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getPriority()
     */
    public function testGetPriority()
    {
        $this->assertEquals($this->caseData[CaseItem::PRIORITY], $this->caseItem->getPriority());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setPriority()
     */
    public function testSetPriority()
    {
        $priorityId = \Dem\HelpDesk\Model\Source\CaseItem\Priority::CASE_PRIORITY_CRITICAL;
        $this->caseItem->setPriority($priorityId);
        $this->assertEquals($priorityId, $this->caseItem->getData(CaseItem::PRIORITY));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getRemoteIp()
     */
    public function testGetRemoteIp()
    {
        $this->assertEquals($this->caseData[CaseItem::REMOTE_IP], $this->caseItem->getRemoteIp());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setRemoteIp()
     */
    public function testSetRemoteIp()
    {
        $remoteIp = '192.168.1.100';
        $this->caseItem->setRemoteIp($remoteIp);
        $this->assertEquals($remoteIp, $this->caseItem->getData(CaseItem::REMOTE_IP));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getHttpUserAgent()
     */
    public function testGetHttpUserAgent()
    {
        $this->assertEquals($this->caseData[CaseItem::HTTP_USER_AGENT], $this->caseItem->getHttpUserAgent());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setHttpUserAgent()
     */
    public function testSetHttpUserAgent()
    {
        $userAgent = 'A long, silly, user agent string';
        $this->caseItem->setHttpUserAgent($userAgent);
        $this->assertEquals($userAgent, $this->caseItem->getData(CaseItem::HTTP_USER_AGENT));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getUpdaterName()
     */
    public function testGetUpdaterName()
    {
        $this->assertEquals($this->caseData[CaseItem::UPDATER_NAME], $this->caseItem->getUpdaterName());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setUpdaterName()
     */
    public function testSetUpdaterName()
    {
        $updaterName = 'Jack Sprat';
        $this->caseItem->setUpdaterName($updaterName);
        $this->assertEquals($updaterName, $this->caseItem->getData(CaseItem::UPDATER_NAME));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getCreatedAt()
     */
    public function testGetCreatedAt()
    {
        $this->assertEquals($this->caseData[CaseItem::CREATED_AT], $this->caseItem->getCreatedAt());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setCreatedAt()
     */
    public function testSetCreatedAt()
    {
        $createdAt = date('Y-m-d h:m:s');
        $this->caseItem->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $this->caseItem->getData(CaseItem::CREATED_AT));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getUpdatedAt()
     */
    public function testGetUpdatedAt()
    {
        $this->assertEquals($this->caseData[CaseItem::UPDATED_AT], $this->caseItem->getUpdatedAt());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::setUpdatedAt()
     */
    public function testSetUpdatedAt()
    {
        $updatedAt = date('Y-m-d h:m:s');
        $this->caseItem->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $this->caseItem->getData(CaseItem::UPDATED_AT));
    }

    /**************************************************************************/
    /**************************************************************************/

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getInitialReply()
     */
    public function testGetInitialReply()
    {
        /** @var Reply[]|MockObject[] $replies */
        /** @var SearchResults|MockObject $searchResults */
        $replies = [$this->objectManager->get(Reply::class)];
        $searchResults = $this->objectManager->get(SearchResults::class);
        $searchResults->setItems($replies);

        // Test false (no initial reply found)
        $this->assertFalse($this->caseMock->getInitialReply($replies));

        $this->caseMock->expects($this->once())
            ->method('getReplies')
            ->willReturn($searchResults);

        // Test empty relplies
        $this->assertFalse($this->caseMock->getInitialReply([]));

        // Test returned reply (initial)
        $replies[0]->setData(Reply::IS_INITIAL, 1);
        $this->assertInstanceOf(Reply::class, $this->caseMock->getInitialReply($replies));
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getRepliesToSave()
     */
    public function testGetRepliesToSave()
    {
        return $this->assertIsArray($this->caseItem->getRepliesToSave());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::addReplyToSave()
     */
    public function testAddReplyToSave()
    {
        /** @var Reply|MockObject $reply */
        $reply = $this->objectManager->get(Reply::class);
        $this->assertInstanceOf(CaseItem::class, $this->caseItem->addReplyToSave($reply));
        $this->assertContains($reply, $this->caseItem->getRepliesToSave());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::clearRepliesToSave()
     */
    public function testClearRepliesToSave()
    {
        /** @var Reply|MockObject $reply */
        $reply = $this->objectManager->get(Reply::class);
        $this->caseItem->addReplyToSave($reply);
        $this->assertNotEmpty($this->caseItem->getRepliesToSave());
        $this->assertInstanceOf(CaseItem::class, $this->caseItem->clearRepliesToSave());
        $this->assertEmpty($this->caseItem->getRepliesToSave());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::getFollowersToSave()
     */
    public function testGetFollowersToSave()
    {
        return $this->assertIsArray($this->caseItem->getFollowersToSave());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::addFollowerToSave()
     */
    public function testAddFollowerToSave()
    {
        /** @var Follower|MockObject $follower */
        $follower = $this->objectManager->get(Follower::class);
        $this->assertInstanceOf(CaseItem::class, $this->caseItem->addFollowerToSave($follower));
        $this->assertContains($follower, $this->caseItem->getFollowersToSave());
    }

    /**
     * @covers \Dem\HelpDesk\Model\CaseItem::clearFollowersToSave()
     */
    public function testClearFollowersToSave()
    {
        /** @var Follower|MockObject $follower */
        $follower = ObjectManager::getInstance()->get(Follower::class);
        $this->caseItem->addFollowerToSave($follower);
        $this->assertNotEmpty($this->caseItem->getFollowersToSave());
        $this->assertInstanceOf(CaseItem::class, $this->caseItem->clearFollowersToSave());
        $this->assertEmpty($this->caseItem->getFollowersToSave());
    }

}
