<?php

declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Block\Adminhtml\CaseItem\View;

use Dem\HelpDesk\Block\Adminhtml\CaseItem\View\Tabs;
use Dem\HelpDesk\Helper\Data as Helper;
use Dem\HelpDesk\Model\CaseItem;
use Dem\HelpDesk\Model\Follower;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\Source\CaseItem\Priority;
use Dem\HelpDesk\Model\Source\CaseItem\Status;
use Dem\HelpDesk\Model\User as HelpDeskUser;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\App\ObjectManager;
use Magento\User\Model\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Block Adminhtml CaseItem View Tabs
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class AdminCaseViewTabsTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * Tabs
     *
     * @var MockObject|Tabs
     */
    protected $tabsMock;

    /**
     * @var MockObject|Reply
     */
    protected $replyMock;

    /**
     * @var MockObject|CaseItem
     */
    protected $caseMock;

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
        CaseItem::STATUS_ID => Status::CASE_STATUS_RESOLVED,
        CaseItem::PRIORITY => Priority::CASE_PRIORITY_NORMAL,
        CaseItem::REMOTE_IP => '0.0.0.0',
        CaseItem::HTTP_USER_AGENT => null,
        CaseItem::UPDATER_NAME => 'Super Admin',
        CaseItem::CREATED_AT => '2021-04-07 23:39:53',
        CaseItem::UPDATED_AT => '2021-04-07 23:52:51',
        CaseItem::WEBSITE_NAME => 'DE Internal',
        CaseItem::DEPARTMENT_NAME => 'Default Department',
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->tabsMock = $this->getMockBuilder(Tabs::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getCase',
                'formatDate',
                'getStatusSource',
                'getPrioritySource',
                'getHelpDeskUserById',
                'getAdminUser',
                'getCaseManagerId',
            ])
            ->getMock();

        $this->caseMock = $this->getMockBuilder(CaseItem::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getReplies',
                'getFollowers'
            ])
            ->getMock();

        $this->caseMock->setData($this->caseData);
    }

    /**
     * @covers \Dem\HelpDesk\Block\Adminhtml\CaseItem\View\Tabs::getVisibleReplies()
     */
    public function testGetVisibleReplies()
    {
        $reply1 = $this->objectManager->create(Reply::class)
            ->addData([
                'id' => 1,
                Reply::IS_INITIAL => 1,
                Reply::AUTHOR_TYPE => Reply::AUTHOR_TYPE_CREATOR
            ]);
        $reply2 = $this->objectManager->create(Reply::class)
            ->addData([
                'id' => 2,
                Reply::IS_INITIAL => 0,
                Reply::AUTHOR_TYPE => Reply::AUTHOR_TYPE_SYSTEM
            ]);
        $reply3 = $this->objectManager->create(Reply::class)
            ->addData([
                'id' => 3,
                Reply::IS_INITIAL => 0,
                Reply::AUTHOR_TYPE => Reply::AUTHOR_TYPE_CASE_MANAGER
            ]);
        $reply4 = $this->objectManager->create(Reply::class)
            ->addData([
                'id' => 4,
                Reply::IS_INITIAL => 0,
                Reply::AUTHOR_TYPE => Reply::AUTHOR_TYPE_HELPDESK_USER
            ]);

        $replies = [
            $reply1, $reply2, $reply3, $reply4
        ];

        $searchResults = $this->objectManager->get(SearchResults::class);
        $searchResults->setItems($replies);

        $this->tabsMock->expects($this->once())
            ->method('getCase')
            ->willReturn($this->caseMock);

        $this->caseMock->expects($this->once())
            ->method('getReplies')
            ->willReturn($searchResults);

        // All replies
        $visibleReplies = $this->tabsMock->getVisibleReplies();
        $this->assertIsArray($visibleReplies);
        $this->assertEquals(4, count($visibleReplies));

        // Limit 2
        $visibleReplies = $this->tabsMock->getVisibleReplies(2);
        $this->assertIsArray($visibleReplies);
        $this->assertEquals(2, count($visibleReplies));

        // Include initial, exclude system
        $visibleReplies = $this->tabsMock->getVisibleReplies(0, true, false);
        $this->assertIsArray($visibleReplies);
        $this->assertNotContains($reply2, $visibleReplies);

        // Include system, exclude initial
        $visibleReplies = $this->tabsMock->getVisibleReplies(0, false, true);
        $this->assertIsArray($visibleReplies);
        $this->assertNotContains($reply1, $visibleReplies);
    }

    /**
     * @covers \Dem\HelpDesk\Block\Adminhtml\CaseItem\View\Tabs::getStatusItem()
     */
    public function testGetStatusItem()
    {
        /** @var Status|MockObject $options */
        $options = $this->objectManager->get(Status::class)->getOptions();
        $expectedStatusItem = $options
            ->getItemByColumnValue('id', Status::CASE_STATUS_RESOLVED);

        /** @var Status|MockObject $statusSource */
        $statusSource = $this->getMockBuilder(Status::class)
            ->disableOriginalConstructor()
            ->setMethods(['getOptions'])
            ->getMock();

        $this->tabsMock->expects($this->once())
            ->method('getStatusSource')
            ->willReturn($statusSource);

        $statusSource->expects($this->once())
            ->method('getOptions')
            ->willReturn($options);

        $this->assertSame($expectedStatusItem, $this->tabsMock->getStatusItem($this->caseMock));
    }

    /**
     * @covers \Dem\HelpDesk\Block\Adminhtml\CaseItem\View\Tabs::getPriorityItem()
     */
    public function testGetPriorityItem()
    {
        /** @var Priority|MockObject $options */
        $options = $this->objectManager->get(Priority::class)->getOptions();
        $expectedStatusItem = $options
            ->getItemByColumnValue('id', Priority::CASE_PRIORITY_NORMAL);

        /** @var Priority|MockObject $statusSource */
        $prioritySource = $this->getMockBuilder(Priority::class)
            ->disableOriginalConstructor()
            ->setMethods(['getOptions'])
            ->getMock();

        $this->tabsMock->expects($this->once())
            ->method('getPrioritySource')
            ->willReturn($prioritySource);

        $this->tabsMock->expects($this->once())
            ->method('getCase')
            ->willReturn($this->caseMock);

        $prioritySource->expects($this->once())
            ->method('getOptions')
            ->willReturn($options);

        $this->assertSame($expectedStatusItem, $this->tabsMock->getPriorityItem());
    }

    /**
     * @covers \Dem\HelpDesk\Block\Adminhtml\CaseItem\View\Tabs::getReplyClass()
     */
    public function testGetReplyClass()
    {
        $reply = $this->objectManager->create(Reply::class)
            ->addData([
                Reply::AUTHOR_TYPE => Reply::AUTHOR_TYPE_HELPDESK_USER
            ]);

        $expectedClassName = 'helpdesk-user';
        $this->assertEquals($expectedClassName, $this->tabsMock->getReplyClass($reply));
    }

    /**
     * @covers \Dem\HelpDesk\Block\Adminhtml\CaseItem\View\Tabs::getAuthorName()
     */
    public function testGetAuthorName()
    {
        /** @var Reply|MockObject $reply1 */
        /** @var Reply|MockObject $reply2 */
        /** @var Reply|MockObject $reply3 */

        $expectedAuthorName = $this->caseData[CaseItem::CREATOR_NAME];
        $reply1 = $this->objectManager->create(Reply::class)
            ->setData(Reply::AUTHOR_TYPE, Reply::AUTHOR_TYPE_CREATOR);

        $this->tabsMock->expects($this->any())
            ->method('getCase')
            ->willReturn($this->caseMock);

        $this->assertEquals($expectedAuthorName, $this->tabsMock->getAuthorName($reply1));

        $reply2 = $this->getMockBuilder(Reply::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAuthorName'])
            ->getMock();

        $reply2->setData(Reply::AUTHOR_TYPE, Reply::AUTHOR_TYPE_CASE_MANAGER);
        $reply2->setData(Reply::AUTHOR_ID, 1);

        $reply2->expects($this->once())
            ->method('getAuthorName')
            ->willReturn($expectedAuthorName);

        $this->assertEquals($expectedAuthorName, $this->tabsMock->getAuthorName($reply2));

        $reply3 = $this->objectManager->create(Reply::class)
            ->setData(Reply::AUTHOR_TYPE, Reply::AUTHOR_TYPE_CASE_MANAGER);

        $this->assertEmpty($this->tabsMock->getAuthorName($reply3));
    }

    /**
     * @covers \Dem\HelpDesk\Block\Adminhtml\CaseItem\View\Tabs::renderReplyBlock()
     */
    public function testRenderReplyBlock()
    {
        $options = $this->objectManager->get(Status::class)->getOptions();
        $expectedStatusItem = $options
            ->getItemByColumnValue('id', Status::CASE_STATUS_RESOLVED);

        /** @var Tabs|MockObject $renderTabsMock */
        $renderTabsMock = $this->getMockBuilder(Tabs::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getTemplateFile',
                'fetchView',
                'escapeHtml',
                'getReplyClass',
                'getCreatedDate',
                'getAuthorName',
                'getStatusItem',
            ])
            ->getMock();

        $content = '{{reply-class}}::{{reply-date}}::{{reply-text}}::{{reply-author}}::{{reply-status}}::{{status-label}}';
        $expectedValues = [
            0 => 'creator',
            1 => 'Apr 7, 2021 11:39:53 PM',
            2 => 'Now is the time for all good men...',
            3 => Reply::AUTHOR_TYPE_CREATOR,
            4 => $expectedStatusItem->getLabel()->render(),
            5 => 'Current Status'
        ];

        /** @var Reply|MockObject $reply */
        $reply = $this->getMockBuilder(Reply::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getAuthorName',
                'getReplyText',
                'getIsAuthorTypeSystem'
            ])
            ->getMock();

        $reply->expects($this->once())
            ->method('getIsAuthorTypeSystem')
            ->willReturn(false);

        $renderTabsMock->expects($this->once())
            ->method('getTemplateFile')
            ->willReturn(null);

        $renderTabsMock->expects($this->once())
            ->method('fetchView')
            ->willReturn($content);

        $renderTabsMock->expects($this->once())
            ->method('getReplyClass')
            ->willReturn($expectedValues[0]);

        $renderTabsMock->expects($this->once())
            ->method('getCreatedDate')
            ->willReturn($expectedValues[1]);

        $reply->expects($this->once())
            ->method('getReplyText')
            ->willReturn($expectedValues[2]);

        $renderTabsMock->expects($this->once())
            ->method('getAuthorName')
            ->willReturn($expectedValues[3]);

        $renderTabsMock->expects($this->once())
            ->method('getStatusItem')
            ->willReturn($expectedStatusItem);

        $result = $renderTabsMock->renderReplyBlock($reply);
        $this->assertIsString($result);
        $renderArray = explode('::', $result);
        $this->assertSame($expectedValues, $renderArray);
    }

    /**
     * @covers \Dem\HelpDesk\Block\Adminhtml\CaseItem\View\Tabs::getCanRenderFollowerBlock()
     */
    public function testGetCanRenderFollowerBlock()
    {
        $user = $this->objectManager->create(User::class);
        $user->setData('id', 1);

        /** @var HelpDeskUser|MockObject $helpDeskUser */
        $helpDeskUser = $this->objectManager->create(HelpDeskUser::class);
        $helpDeskUser->setData(HelpDeskUser::USER_ID, 1);

        $this->tabsMock->expects($this->any())
            ->method('getAdminUser')
            ->willReturn($user);

        $this->tabsMock->expects($this->any())
            ->method('getCase')
            ->willReturn($this->caseMock);

        $this->tabsMock->expects($this->any())
            ->method('getCaseManagerId')
            ->willReturn(1);

        // User is the case creator
        $this->caseMock->setData(CaseItem::CREATOR_ADMIN_ID, 1);
        $this->assertFalse($this->tabsMock->getCanRenderFollowerBlock());

        // User is the department case manager
        $this->caseMock->unsetData(CaseItem::CREATOR_ADMIN_ID);
        $this->caseMock->setData(CaseItem::CASE_MANAGER, $user);
        $this->assertFalse($this->tabsMock->getCanRenderFollowerBlock());

        // Test user is helpdesk user

        $user->setData('id', 99);
        $this->tabsMock->expects($this->any())
            ->method('getAdminUser')
            ->willReturn($user);

        $this->tabsMock->expects($this->any())
            ->method('getCaseManagerId')
            ->willReturn(99);

        $this->tabsMock->expects($this->any())
            ->method('getHelpDeskUserById')
            ->willReturn($helpDeskUser);

        $this->assertTrue($this->tabsMock->getCanRenderFollowerBlock());

        $helpDeskUser->unsetData(HelpDeskUser::USER_ID, 1);
        $this->tabsMock->expects($this->any())
            ->method('getHelpDeskUserById')
            ->willReturn($helpDeskUser);

        $this->assertFalse($this->tabsMock->getCanRenderFollowerBlock());
    }

    /**
     * @covers \Dem\HelpDesk\Block\Adminhtml\CaseItem\View\Tabs::getIsUserFollower()
     */
    public function testGetIsUserFollower()
    {
        /** @var User|MockObject $user */
        $user = $this->objectManager->create(User::class);
        $user->setData('id', 1);

        /** @var Follower|MockObject $follower */
        $follower = $this->objectManager->get(Follower::class);
        $follower->setData(Follower::USER_ID, 1);

        $followers = [$follower];
        /** @var SearchResults|MockObject $searchResults */
        $searchResults = $this->objectManager->get(SearchResults::class);
        $searchResults->setItems($followers);

        $this->tabsMock->expects($this->any())
            ->method('getAdminUser')
            ->willReturn($user);

        $this->tabsMock->expects($this->any())
            ->method('getCase')
            ->willReturn($this->caseMock);

        $this->caseMock->expects($this->once())
            ->method('getFollowers')
            ->willReturn($searchResults);

        // Allows manipulation of items similar to \Magento\Framework\Data\Collection
        $followerCollection = new \Dem\Base\Data\SearchResultsProcessor($searchResults);

        $this->assertTrue($this->tabsMock->getIsUserFollower());
    }
}
