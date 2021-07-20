<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model\ResourceModel;

use Dem\HelpDesk\Model\CaseItem;
use Dem\HelpDesk\Model\Department;
use Dem\HelpDesk\Model\Follower;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\ResourceModel\CaseItem as Resource;
use Dem\HelpDesk\Model\User;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Store\Api\Data\WebsiteInterface;
use Magento\Store\Model\Website;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - ResourceModel CaseItem
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class CaseItemResourceTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|Resource
     */
    protected $resourceMock;

    /**
     * @var MockObject|CaseItem
     */
    protected $caseItem;

    /**
     * @var MockObject|Reply
     */
    protected $reply;

    /**
     * @var MockObject|Department
     */
    protected $department;

    /**
     * @var MockObject|Follower
     */
    protected $follower;

    /**
     * Test Case data
     * @var []
     */
    protected $caseData = [
        CaseItem::CASE_ID => 1,
        CaseItem::WEBSITE_ID => 1,
        CaseItem::DEPARTMENT_ID => 1,
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
        // CaseItem::WEBSITE_NAME => 'DE Internal',
        // CaseItem::DEPARTMENT_NAME => 'Default Department',
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

        $this->resourceMock = $this->getMockBuilder(Resource::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'save',
                'getWebsite',
                'getSearchCriteriaBuilder',
                'getRepliesList',
                'getFollowersList',
                'getReplyRepository',
                'getFollowerRepository',
                'getDepartmentRepository',
                'getUserRepository',
            ])
            ->getMock();

        $this->caseItem = $this->objectManager->create(CaseItem::class);
        $this->department = $this->objectManager->create(Department::class);
        $this->reply = $this->objectManager->create(Reply::class);
        $this->follower = $this->objectManager->create(Follower::class);
        $this->user = $this->objectManager->create(User::class);

        $this->user->setData($this->userData);

        $this->department->setData($this->deptData);

        $this->caseItem->setData($this->caseData);
        $this->caseItem->setData('_department', $this->department);
    }

    /**
     * @covers \Dem\HelpDesk\Model\ResourceModel\CaseItem::saveReplies()
     */
    public function testSaveReplies()
    {
        $this->caseItem->addReplyToSave($this->reply);
        $this->assertEquals(1, count($this->caseItem->getRepliesToSave()));

        $replyRepository = $this->getMockBuilder(\Dem\HelpDesk\Model\ReplyRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['save'])
            ->getMock();

        $this->resourceMock->expects($this->any())
            ->method('getReplyRepository')
            ->willReturn($replyRepository);

        $result = $this->resourceMock->saveReplies($this->caseItem);
        $this->assertInstanceOf(Resource::class, $result);

        $this->assertEquals(0, count($this->caseItem->getRepliesToSave()));
    }

    /**
     * @covers \Dem\HelpDesk\Model\ResourceModel\CaseItem::saveFollowers()
     */
    public function testSaveFollowers()
    {
        $this->caseItem->addFollowerToSave($this->follower);
        $this->assertEquals(1, count($this->caseItem->getFollowersToSave()));

        $followerRepository = $this->getMockBuilder(\Dem\HelpDesk\Model\FollowerRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['save'])
            ->getMock();

        $this->resourceMock->expects($this->any())
            ->method('getFollowerRepository')
            ->willReturn($followerRepository);

        $result = $this->resourceMock->saveFollowers($this->caseItem);
        $this->assertInstanceOf(Resource::class, $result);

        $this->assertEquals(0, count($this->caseItem->getFollowersToSave()));
    }

    /**
     * @covers \Dem\HelpDesk\Model\ResourceModel\CaseItem::setCaseNumber()
     */
    public function testSetCaseNumber()
    {
        $this->assertInstanceOf(Resource::class, $this->resourceMock->setCaseNumber($this->caseItem));
        $this->assertEquals('001-000001', $this->caseItem->getData(CaseItem::CASE_NUMBER));
    }

    /**
     * @covers \Dem\HelpDesk\Model\ResourceModel\CaseItem::setDepartmentName()
     */
    public function testSetDepartmentName()
    {
        $this->caseItem->setResource();
        $this->assertInstanceOf(Resource::class, $this->resourceMock->setDepartmentName($this->caseItem));
        $this->assertEquals($this->deptData[Department::NAME], $this->caseItem->getData(CaseItem::DEPARTMENT_NAME));
    }

    /**
     * @covers \Dem\HelpDesk\Model\ResourceModel\CaseItem::getDepartment()
     */
    public function testGetDepartmentWithRepository()
    {
        $departmentRepository = $this->getMockBuilder(\Dem\HelpDesk\Model\DepartmentRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['getById'])
            ->getMock();

        $this->resourceMock->expects($this->any())
            ->method('getDepartmentRepository')
            ->willReturn($departmentRepository);

        $departmentRepository->expects($this->any())
            ->method('getById')
            ->willReturn($this->department);

        $this->assertInstanceOf(Department::class, $this->resourceMock->getDepartment($this->caseItem));
    }

    /**
     * @covers \Dem\HelpDesk\Model\ResourceModel\CaseItem::getDepartment()
     */
    public function testGetDepartmentNoRepositoryWithData()
    {
        $this->caseItem->setData('_department', $this->department);

        $this->resourceMock->expects($this->any())
            ->method('getDepartmentRepository')
            ->willReturn(null);

        $this->assertInstanceOf(Department::class, $this->resourceMock->getDepartment($this->caseItem));
    }

    /**
     * @covers \Dem\HelpDesk\Model\ResourceModel\CaseItem::getDepartment()
     */
    public function testGetDepartmentNoRepositoryNoData()
    {
        $this->caseItem->unsetData('_department');

        $this->resourceMock->expects($this->any())
            ->method('getDepartmentRepository')
            ->willReturn(null);

        $this->assertInstanceOf(Department::class, $this->resourceMock->getDepartment($this->caseItem));
    }

    /**
     * @covers \Dem\HelpDesk\Model\ResourceModel\CaseItem::setWebsiteName()
     */
    public function testSetWebsiteName()
    {
        $website = new DataObject(['id' => 1, 'name' => 'DE Internal']);
        $this->resourceMock->expects($this->once())
            ->method('getWebsite')
            ->willReturn($website);

        $this->assertInstanceOf(Resource::class, $this->resourceMock->setWebsiteName($this->caseItem));
        $this->assertEquals('DE Internal', $this->caseItem->getData(CaseItem::WEBSITE_NAME));
    }

    /**
     * @covers \Dem\HelpDesk\Model\ResourceModel\CaseItem::setCaseManager()
     */
    public function testSetCaseManager()
    {
        $this->assertInstanceOf(Resource::class, $this->resourceMock->setCaseManager($this->caseItem));
        $caseManager = $this->caseItem->getData(CaseItem::CASE_MANAGER);
        $this->assertInstanceOf(User::class, $caseManager);
    }

    /**
     * @covers \Dem\HelpDesk\Model\ResourceModel\CaseItem::setCaseManager()
     */
    public function testSetCaseManagerWithRepository()
    {
        $userRepository = $this->getMockBuilder(\Dem\HelpDesk\Model\UserRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['getById'])
            ->getMock();

        $this->resourceMock->expects($this->any())
            ->method('getUserRepository')
            ->willReturn($userRepository);

        $userRepository->expects($this->any())
            ->method('getById')
            ->willReturn($this->user);

        $this->assertInstanceOf(Resource::class, $this->resourceMock->setCaseManager($this->caseItem));
    }

    /**
     * @covers \Dem\HelpDesk\Model\ResourceModel\CaseItem::getReplies()
     */
    public function testGetReplies()
    {
        /** @var SearchCriteriaBuilder|MockObject $resource */
        $searchCriteriaBuilderMock = $this->getMockBuilder(SearchCriteriaBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'addFilter',
                'setSortOrders',
                'create'
            ])
            ->getMock();

        /** @var SearchCriteria|MockObject $searchCriteria */
        /** @var SearchResultsInterface|MockObject $searchResults */
        $searchCriteria = $this->objectManager->get(SearchCriteria::class);
        $searchResults = $this->objectManager->get(SearchResultsInterface::class);

        $this->resourceMock->expects($this->once())
            ->method('getSearchCriteriaBuilder')
            ->willReturn($searchCriteriaBuilderMock);

        $this->resourceMock->expects($this->once())
            ->method('getRepliesList')
            ->willReturn($searchResults);

        $searchCriteriaBuilderMock->expects($this->once())
            ->method('addFilter')
            ->will($this->returnSelf());

        $searchCriteriaBuilderMock->expects($this->once())
            ->method('setSortOrders')
            ->will($this->returnSelf());

        $searchCriteriaBuilderMock->expects($this->once())
            ->method('create')
            ->willReturn($searchCriteria);

        $this->assertInstanceOf(SearchResultsInterface::class, $this->resourceMock->getReplies($this->caseItem));
    }

    /**
     * @covers \Dem\HelpDesk\Model\ResourceModel\CaseItem::getFollowers()
     */
    public function testGetFollowers()
    {
        /** @var SearchCriteriaBuilder|MockObject $searchCriteriaBuilderMock */
        $searchCriteriaBuilderMock = $this->getMockBuilder(SearchCriteriaBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'addFilter',
                'setSortOrders',
                'create'
            ])
            ->getMock();

        /** @var SearchCriteria|MockObject $searchCriteria */
        /** @var SearchResultsInterface|MockObject $searchResults */
        $searchCriteria = $this->objectManager->get(SearchCriteria::class);
        $searchResults = $this->objectManager->get(SearchResultsInterface::class);

        $this->resourceMock->expects($this->once())
            ->method('getSearchCriteriaBuilder')
            ->willReturn($searchCriteriaBuilderMock);

        $this->resourceMock->expects($this->once())
            ->method('getFollowersList')
            ->willReturn($searchResults);

        $searchCriteriaBuilderMock->expects($this->once())
            ->method('addFilter')
            ->will($this->returnSelf());

        $searchCriteriaBuilderMock->expects($this->once())
            ->method('create')
            ->willReturn($searchCriteria);

        $this->assertInstanceOf(SearchResultsInterface::class, $this->resourceMock->getFollowers($this->caseItem));
    }
}
