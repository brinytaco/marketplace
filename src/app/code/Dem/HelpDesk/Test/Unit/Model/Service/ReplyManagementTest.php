<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model\Service;

use Dem\HelpDesk\Model\CaseItem;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\Service\ReplyManagement;
use Dem\HelpDesk\Model\Source\CaseItem\Status;
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
class ReplyManagementTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|ReplyManagement
     */
    protected $modelManager;

    /**
     *
     * @var MockObject|CaseItem
     */
    protected $caseItem;

    /**
     *
     * @var MockObject|Reply
     */
    protected $reply;

    /**
     * Test Reply data
     * @var []
     */
    protected $replyData = [
        Reply::CASE_ID => 1,
        Reply::STATUS_ID => Status::CASE_STATUS_NEW,
        Reply::AUTHOR_ID => 1,
        Reply::REPLY_TEXT => 'Now is the time for all good men to come to the aid of their country.',
        Reply::AUTHOR_TYPE => Reply::AUTHOR_TYPE_CREATOR,
        Reply::IS_INITIAL => 0
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->modelManager = $this->getMockBuilder(ReplyManagement::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getHelper',
            ])
            ->getMock();

        $this->reply = $this->objectManager->create(Reply::class);

        $this->caseItem = $this->objectManager->create(CaseItem::class);
        $this->caseItem->addData([
            Reply::CASE_ID => $this->replyData[Reply::CASE_ID],
            Reply::STATUS_ID => $this->replyData[Reply::STATUS_ID]
        ]);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\ReplyManagement::createReply()
     */
    public function testCreateReply()
    {
        $authorId = $this->replyData[Reply::AUTHOR_ID];
        $authorType = $this->replyData[Reply::AUTHOR_TYPE];
        $replyText = $this->replyData[Reply::REPLY_TEXT];

        $newReply = $this->modelManager->createReply($this->reply, $this->caseItem, $authorId, $authorType, $replyText);

        $this->assertInstanceOf(Reply::class, $newReply);
        $this->assertEquals($this->replyData[Reply::CASE_ID], $newReply->getData(Reply::CASE_ID));
        $this->assertEquals($this->replyData[Reply::STATUS_ID], $newReply->getData(Reply::STATUS_ID));
        $this->assertEquals($this->replyData[Reply::AUTHOR_ID], $newReply->getData(Reply::AUTHOR_ID));
        $this->assertEquals($this->replyData[Reply::REPLY_TEXT], $newReply->getData(Reply::REPLY_TEXT));
        $this->assertEquals($this->replyData[Reply::AUTHOR_TYPE], $newReply->getData(Reply::AUTHOR_TYPE));
        $this->assertEquals($this->replyData[Reply::IS_INITIAL], $newReply->getData(Reply::IS_INITIAL));
        $this->assertNull($newReply->getData(Reply::REMOTE_IP));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\ReplyManagement::createInitialReply()
     */
    public function testCreateInitialReply()
    {
        $authorId = $this->replyData[Reply::AUTHOR_ID];
        $replyText = $this->replyData[Reply::REPLY_TEXT];

        $newReply = $this->modelManager->createInitialReply($this->reply, $this->caseItem, $authorId, $replyText);

        $this->assertInstanceOf(Reply::class, $newReply);
        $this->assertEquals($this->replyData[Reply::CASE_ID], $newReply->getData(Reply::CASE_ID));
        $this->assertEquals($this->replyData[Reply::STATUS_ID], $newReply->getData(Reply::STATUS_ID));
        $this->assertEquals($this->replyData[Reply::AUTHOR_ID], $newReply->getData(Reply::AUTHOR_ID));
        $this->assertEquals($this->replyData[Reply::REPLY_TEXT], $newReply->getData(Reply::REPLY_TEXT));
        $this->assertEquals(Reply::AUTHOR_TYPE_CREATOR, $newReply->getData(Reply::AUTHOR_TYPE));
        $this->assertEquals(1, $newReply->getData(Reply::IS_INITIAL));
        $this->assertNull($newReply->getData(Reply::REMOTE_IP));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\ReplyManagement::createSystemReply()
     */
    public function testCreateSystemReply()
    {
        $replyText = 'System reply text';

        $newReply = $this->modelManager->createSystemReply($this->reply, $this->caseItem, $replyText);

        $this->assertInstanceOf(Reply::class, $newReply);
        $this->assertEquals($this->replyData[Reply::CASE_ID], $newReply->getData(Reply::CASE_ID));
        $this->assertEquals($this->replyData[Reply::STATUS_ID], $newReply->getData(Reply::STATUS_ID));
        $this->assertNull($newReply->getData(Reply::AUTHOR_ID));
        $this->assertEquals($replyText, $newReply->getData(Reply::REPLY_TEXT));
        $this->assertEquals(Reply::AUTHOR_TYPE_SYSTEM, $newReply->getData(Reply::AUTHOR_TYPE));
        $this->assertEquals($newReply->getData(Reply::IS_INITIAL), $newReply->getData(Reply::IS_INITIAL));
        $this->assertNull($newReply->getData(Reply::REMOTE_IP));
    }

    /**
     * Covers void return value + 1st Exception block
     *
     * @covers \Dem\HelpDesk\Model\Service\ReplyManagement::validate()
     */
    public function testValidate1()
    {
        $this->assertNull($this->modelManager->validate($this->replyData));

        $this->replyData[Reply::REPLY_TEXT] = '';
        $this->expectExceptionObject(
            new \Dem\HelpDesk\Exception(__('The %1 `%2` cannot be empty', 'reply', Reply::REPLY_TEXT))
        );
        $this->modelManager->validate($this->replyData);
    }

    /**
     * Covers 2nd Exception block
     *
     * @covers \Dem\HelpDesk\Model\Service\ReplyManagement::validate()
     */
    public function testValidate2()
    {
        unset($this->replyData[Reply::REPLY_TEXT]);
        $this->expectExceptionObject(
            new \Dem\HelpDesk\Exception(__('The %1 `%2` cannot be empty','reply', Reply::REPLY_TEXT))
        );
        $this->modelManager->validate($this->replyData);
    }

    /**
     * Covers return of empty required array
     *
     * @covers \Dem\HelpDesk\Model\Service\ReplyManagement::validate()
     */
    public function testValidate3()
    {
        /** @var ReplyManagement|MockObject $modelManagerMock */
        $modelManagerMock = $this->getMockBuilder(ReplyManagement::class)
            ->setMethods([
                'getRequiredFields'
            ])
            ->getMock();

        $modelManagerMock->expects($this->once())
            ->method('getRequiredFields')
            ->willReturn([]);

        $modelManagerMock->validate($this->replyData);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\ReplyManagement::getRequiredFields()
     */
    public function testGetRequiredFields()
    {
        $fields = [
            Reply::REPLY_TEXT,
            REPLY::AUTHOR_TYPE
        ];

        $this->assertSame($fields, $this->modelManager->getRequiredFields());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\ReplyManagement::getEditableFields()
     */
    public function testGetEditableFields()
    {
        $this->assertSame([], $this->modelManager->getEditableFields());
    }
}
