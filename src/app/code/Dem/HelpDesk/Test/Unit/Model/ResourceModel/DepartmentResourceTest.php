<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model\ResourceModel;

use Dem\HelpDesk\Model\Department;
use Dem\HelpDesk\Model\Follower;
use Dem\HelpDesk\Model\User;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use Dem\HelpDesk\Model\ResourceModel\Department as Resource;

/**
 * HelpDesk Unit Test - ResourceModel Department
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class DepartmentResourceTest extends TestCase
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
     * @var MockObject|Department
     */
    protected $department;

    /**
     * @var MockObject|User
     */
    protected $user;

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
                'getUserByCaseManagerId',
                'getSearchCriteriaBuilder',
                'getDefaultFollowersList'
            ])
            ->getMock();

        $this->department = $this->objectManager->create(Department::class);
        $this->user = $this->objectManager->create(User::class);
        $this->user->setData($this->userData);
    }

    /**
     * @covers \Dem\HelpDesk\Model\ResourceModel\Department::setCaseManagerData()
     */
    public function testSetCaseManagerData()
    {
        $this->resourceMock->expects($this->once())
            ->method('getUserByCaseManagerId')
            ->willReturn($this->user);

        $this->assertInstanceOf(Resource::class, $this->resourceMock->setCaseManagerData($this->department));
        $this->assertEquals($this->userData[User::NAME], $this->department->getData(Department::CASE_MANAGER_NAME));
        $this->assertEquals($this->userData[User::EMAIL], $this->department->getData(Department::CASE_MANAGER_EMAIL));
    }

    /**
     * @covers \Dem\HelpDesk\Model\ResourceModel\Department::setDefaultFollowers()
     */
    public function testSetDefaultFollowers()
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

        /** @var Follower|MockObject $followers */
        /** @var SearchResults|MockObject $searchResults */
        $followers = [$this->objectManager->get(Follower::class)->setData($this->userData)];
        $searchResults = $this->objectManager->get(SearchResults::class);
        $searchResults->setItems($followers);

        /** @var SearchCriteria|MockObject $searchCriteria */
        $searchCriteria = $this->objectManager->get(SearchCriteria::class);

        $this->resourceMock->expects($this->once())
            ->method('getSearchCriteriaBuilder')
            ->willReturn($searchCriteriaBuilderMock);

        $searchCriteriaBuilderMock->expects($this->exactly(2))
            ->method('addFilter')
            ->will($this->returnSelf());
        $searchCriteriaBuilderMock->expects($this->once())
            ->method('create')
            ->willReturn($searchCriteria);

        $this->resourceMock->expects($this->once())
            ->method('getDefaultFollowersList')
            ->willReturn($searchResults);

        $this->assertInstanceOf(Resource::class, $this->resourceMock->setDefaultFollowers($this->department));
        $this->assertEquals(count($followers), count($this->department->getData(Department::DEFAULT_FOLLOWERS)));
    }
}
