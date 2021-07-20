<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Controller\CaseItem;

use Dem\HelpDesk\Controller\Adminhtml\CaseItem\Save as Controller;
use Dem\HelpDesk\Exception as HelpDeskException;
use Dem\HelpDesk\Model\CaseItem;
use Dem\HelpDesk\Model\CaseItemFactory;
use Dem\HelpDesk\Model\CaseItemRepository;
use Dem\HelpDesk\Model\Department;
use Dem\HelpDesk\Model\Follower;
use Dem\HelpDesk\Model\FollowerRepository;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\ReplyRepository;
use Dem\HelpDesk\Model\Service\CaseItemManagement;
use Dem\HelpDesk\Model\Service\FollowerManagement;
use Dem\HelpDesk\Model\Service\Notifications;
use Dem\HelpDesk\Model\Service\ReplyManagement;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\Registry;
use Magento\User\Model\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\Type\VoidType;

/**
 * HelpDesk Unit Test - Controller Adminhtml CaseItem Save
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class AdminControllerCaseSaveTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;
    /**
     * @var MockObject|Controller
     */
    protected $controller;

    /**
     * @var MockObject|CaseItem
     */
    protected $caseMock;

    /**
     * @var MockObject|Redirect
     */
    protected $resultRedirect;

    /**
     * @var MockObject|User
     */
    protected $creator;

    /**
     * Test Case data
     * @var []
     */
    protected $caseData = [
        CaseItem::CASE_ID => 1,
        CaseItem::CASE_NUMBER => '001-000001'
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->controller = $this->getMockBuilder(Controller::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getRedirect',
                'isValidPostRequest',
                'getParam',
                'getAdminUser',
                'buildCase',
                'buildSystemReply',
                'buildInitialReply',
                'prepareDefaultFollowers',
                'getCaseItemRepository',
                'getNotificationService',
                'getCoreRegistry',
                'getMessageManager',
                '__',
                'getCaseItemManager',
            ])
            ->getMock();

        $this->caseMock = $this->getMockBuilder(CaseItem::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getDepartment',
                'addReplyToSave',
                'getCaseManager'
            ])
            ->getMock();

        $this->caseMock->setData($this->caseData);

        $this->resultRedirect = $this->getMockBuilder(Redirect::class)
            ->disableOriginalConstructor()
            ->setMethods(['setPath'])
            ->getMock();

        $this->creator = $this->objectManager->get(User::class);
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\CaseItem\Save::execute()
     */
    public function testExecuteSave()
    {
        /** @var User|MockObject $user */
        /** @var Reply|MockObject $reply */
        /** @var Department|MockObject $department */
        /** @var Notifications|MockObject $notification */
        /** @var Registry|MockObject $registry */
        /** @var MessageManagerInterface|MockObject $messageManager */
        /** @var CaseItemRepository|MockObject $caseRepository */
        $user = $this->objectManager->get(User::class);
        $reply = $this->objectManager->get(Reply::class);
        $department = $this->objectManager->get(Department::class);
        $notification = $this->objectManager->get(Notifications::class);
        $registry = $this->objectManager->get(Registry::class);
        $messageManager = $this->objectManager->get(MessageManagerInterface::class);
        $caseRepository = $this->getMockBuilder(CaseItemRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['save'])
            ->getMock();
        $caseManager = $this->getMockBuilder(CaseItemManagement::class)
            ->disableOriginalConstructor()
            ->setMethods(['getDepartment'])
            ->getMock();

        $this->controller->expects($this->once())
            ->method('getRedirect')
            ->willReturn($this->resultRedirect);

        $this->controller->expects($this->once())
            ->method('isValidPostRequest')
            ->willReturn(true);

        $this->controller->expects($this->once())
            ->method('getParam')
            ->willReturn($this->caseData);

        $this->controller->expects($this->once())
            ->method('getAdminUser')
            ->willReturn($user);

        $this->controller->expects($this->once())
            ->method('buildCase')
            ->willReturn($this->caseMock);

        $this->caseMock->expects($this->once())
            ->method('getCaseManager')
            ->willReturn($user);

        $this->controller->expects($this->once())
            ->method('buildSystemReply')
            ->willReturn($reply);

        $this->controller->expects($this->once())
            ->method('buildInitialReply')
            ->willReturn($reply);

        $this->controller->expects($this->once())
            ->method('getCaseItemManager')
            ->willReturn($caseManager);

        $caseManager->expects($this->once())
            ->method('getDepartment')
            ->willReturn($department);

        $this->controller->expects($this->once())
            ->method('prepareDefaultFollowers')
            ->willReturn(VoidType::class);

        $this->controller->expects($this->once())
            ->method('getCaseItemRepository')
            ->willReturn($caseRepository);

        $caseRepository->expects($this->once())
            ->method('save')
            ->willReturn($this->caseMock);

        $this->controller->expects($this->once())
            ->method('getNotificationService')
            ->willReturn($notification);

        $this->controller->expects($this->once())
            ->method('getCoreRegistry')
            ->willReturn($registry);

        $this->controller->expects($this->once())
            ->method('getMessageManager')
            ->willReturn($messageManager);

        $this->assertInstanceOf(Redirect::class, $this->controller->execute());
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\CaseItem\Save::execute()
     */
    public function testExecuteInvalid()
    {
        $this->controller->expects($this->once())
            ->method('getRedirect')
            ->willReturn($this->resultRedirect);

        $this->controller->expects($this->once())
            ->method('isValidPostRequest')
            ->willReturn(false);

        $this->assertInstanceOf(Redirect::class, $this->controller->execute());
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\CaseItem\Save::execute()
     */
    public function testThrowsHelpDeskException()
    {
        /** @var MessageManagerInterface|MockObject $messageManager */
        $messageManager = $this->objectManager->get(MessageManagerInterface::class);

        $this->controller->expects($this->once())
            ->method('getRedirect')
            ->willReturn($this->resultRedirect);

        $this->controller->expects($this->once())
            ->method('isValidPostRequest')
            ->willReturn(true);

        $this->controller->expects($this->once())
            ->method('getParam')
            ->willReturn($this->caseData);

        $this->controller->expects($this->once())
            ->method('getAdminUser')
            ->willThrowException(new HelpDeskException(__('exception')));

        $this->controller->expects($this->once())
            ->method('getMessageManager')
            ->willReturn($messageManager);

        $this->controller->execute();
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\CaseItem\Save::execute()
     */
    public function testThrowsException()
    {
        /** @var MessageManagerInterface|MockObject $messageManager */
        $messageManager = $this->objectManager->get(MessageManagerInterface::class);

        $this->controller->expects($this->once())
            ->method('getRedirect')
            ->willReturn($this->resultRedirect);

        $this->controller->expects($this->once())
            ->method('isValidPostRequest')
            ->willReturn(true);

        $this->controller->expects($this->once())
            ->method('getParam')
            ->willReturn($this->caseData);

        $this->controller->expects($this->once())
            ->method('getAdminUser')
            ->willThrowException(new \Exception('exception'));

        $this->controller->expects($this->once())
            ->method('getMessageManager')
            ->willReturn($messageManager);

        $this->controller->execute();
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\CaseItem\Save::buildCase()
     */
    public function testBuildCase()
    {
        // New instance with mocked methods
        /** @var MockObject|Controller $controller */
        $controller = $this->getMockBuilder(Controller::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getCaseItemManager',
                'getCaseItemFactory',
            ])
            ->getMock();

        /** @var CaseItemManagement|MockObject $manager */
        $manager = $this->getMockBuilder(CaseItemManagement::class)
            ->disableOriginalConstructor()
            ->setMethods(['createCase'])
            ->getMock();

        /** @var CaseItemFactory|MockObject $caseFactory */
        $caseFactory = $this->getMockBuilder(CaseItemFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $controller->expects($this->once())
            ->method('getCaseItemManager')
            ->willReturn($manager);

        $manager->expects($this->once())
            ->method('createCase')
            ->willReturn($this->caseMock);

        $controller->expects($this->once())
            ->method('getCaseItemFactory')
            ->willReturn($caseFactory);

        $caseFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->caseMock);

        $result = $controller->buildCase($this->creator, $this->caseData);
        $this->assertInstanceOf(CaseItem::class, $result);
        $this->assertEquals($this->caseData[CaseItem::CASE_NUMBER], $result->getCaseNumber());
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\CaseItem\Save::buildInitialReply()
     */
    public function testBuildInitialReply()
    {
        // New instance with mocked methods
        /** @var MockObject|Controller $controller */
        $controller = $this->getMockBuilder(Controller::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getReplyManager',
                'getReplyFactory',
            ])
            ->getMock();

        $this->creator->setData('id', 1);

        /** @var Reply|MockObject $reply */
        $reply = $this->objectManager->get(Reply::class);
        $reply->addData([
            Reply::AUTHOR_TYPE => Reply::AUTHOR_TYPE_CREATOR,
            Reply::AUTHOR_ID => $this->creator->getId()
        ]);

        /** @var ReplyManagement|MockObject $manager */
        $manager = $this->getMockBuilder(ReplyManagement::class)
            ->disableOriginalConstructor()
            ->setMethods(['createInitialReply'])
            ->getMock();

        /** @var ReplyFactory|MockObject $replyFactory */
        $replyFactory = $this->getMockBuilder(ReplyFactory::class)
        ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $controller->expects($this->once())
            ->method('getReplyManager')
            ->willReturn($manager);

        $manager->expects($this->once())
            ->method('createInitialReply')
            ->willReturn($reply);

        $controller->expects($this->once())
            ->method('getReplyFactory')
            ->willReturn($replyFactory);

        $replyFactory->expects($this->once())
            ->method('create')
            ->willReturn($reply);

        $this->caseData['reply_text'] = 'Just a simple reply test';

        $result = $controller->buildInitialReply($this->caseMock, $this->creator, $this->caseData);
        $this->assertInstanceOf(Reply::class, $result);
        $this->assertEquals(Reply::AUTHOR_TYPE_CREATOR, $reply->getData(Reply::AUTHOR_TYPE));
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\CaseItem\Save::buildSystemReply()
     */
    public function testBuildSystemReply()
    {
        // New instance with mocked methods
        /** @var MockObject|Controller $controller */
        $controller = $this->getMockBuilder(Controller::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getReplyManager',
                'getReplyFactory',
            ])
            ->getMock();

        $this->creator->setData('id', 1);
        /** @var Reply|MockObject $reply */
        $reply = $this->objectManager->get(Reply::class);
        $reply->addData([
            Reply::AUTHOR_TYPE => Reply::AUTHOR_TYPE_SYSTEM,
            Reply::AUTHOR_ID => null
        ]);

        /** @var ReplyManagement|MockObject $manager */
        $manager = $this->getMockBuilder(ReplyManagement::class)
            ->disableOriginalConstructor()
            ->setMethods(['createInitialReply'])
            ->getMock();

        /** @var ReplyFactory|MockObject $replyFactory */
        $replyFactory = $this->getMockBuilder(ReplyFactory::class)
        ->disableOriginalConstructor()
        ->setMethods(['create'])
        ->getMock();

        $controller->expects($this->once())
            ->method('getReplyManager')
            ->willReturn($manager);

        $controller->expects($this->once())
            ->method('getReplyFactory')
            ->willReturn($replyFactory);

        $replyFactory->expects($this->once())
            ->method('create')
            ->willReturn($reply);

        $this->caseData['message'] = 'Just a system message';

        $result = $controller->buildSystemReply($this->caseMock, $this->creator, $this->caseData);
        $this->assertInstanceOf(Reply::class, $result);
        $this->assertEquals(Reply::AUTHOR_TYPE_SYSTEM, $reply->getData(Reply::AUTHOR_TYPE));
    }

    /**
     * @covers \Dem\HelpDesk\Controller\Adminhtml\CaseItem\Save::prepareDefaultFollowers()
     */
    public function testPrepareDefaultFollowers()
    {
        /** @var MockObject|Follower $follower */
        $follower = $this->objectManager->get(Follower::class);

        // New instance with mocked methods
        /** @var MockObject|Controller $controller */
        $controller = $this->getMockBuilder(Controller::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getFollowerManager',
                'getFollowerFactory',
            ])
            ->getMock();

        /** @var FollowerManagement|MockObject $manager */
        $manager = $this->getMockBuilder(FollowerManagement::class)
            ->disableOriginalConstructor()
            ->setMethods(['createFollower'])
            ->getMock();

        /** @var FollowerFactory|MockObject $followerFactory */
        $followerFactory = $this->getMockBuilder(FollowerFactory::class)
        ->disableOriginalConstructor()
        ->setMethods(['create'])
        ->getMock();

        /** @var Department|MockObject $department */
        $department = $this->getMockBuilder(Department::class)
            ->disableOriginalConstructor()
            ->setMethods(['getDefaultFollowers'])
            ->getMock();

        $controller->expects($this->once())
            ->method('getFollowerManager')
            ->willReturn($manager);

        $manager->expects($this->any())
            ->method('createFollower')
            ->willReturn($follower);

        $controller->expects($this->once())
            ->method('getFollowerFactory')
            ->willReturn($followerFactory);

        $followerFactory->expects($this->any())
            ->method('create')
            ->willReturn($follower);

        $defaultFollowers = [1, 2, 3, 4, 5];
        $department->expects($this->once())
            ->method('getDefaultFollowers')
            ->willReturn($defaultFollowers);

        $result = $controller->prepareDefaultFollowers($this->caseMock, $department);
        $this->assertInstanceOf(CaseItem::class, $result);
        $this->assertEquals(count($defaultFollowers), count($this->caseMock->getFollowersToSave()));
    }
}
