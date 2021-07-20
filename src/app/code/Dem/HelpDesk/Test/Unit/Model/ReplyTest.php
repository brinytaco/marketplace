<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model;

use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\ResourceModel\Reply as Resource;
use Dem\HelpDesk\Model\Source\CaseItem\Status;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Registry;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Model Reply
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class ReplyTest extends TestCase
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
     * @var MockObject|Reply
     */
    protected $reply;

    /**
     * @var MockObject|Resource
     */
    protected $resourceMock;

    /**
     * Test Department data
     * @var []
     */
    protected $replyData = [
        Reply::REPLY_ID => 1,
        Reply::CASE_ID => 1,
        Reply::AUTHOR_ID => 1,
        Reply::AUTHOR_TYPE => Reply::AUTHOR_TYPE_CREATOR,
        Reply::REPLY_TEXT => 'Now is the time for all good men to come to the aid of their country.',
        Reply::REMOTE_IP => '127.0.0.1',
        Reply::STATUS_ID => Status::CASE_STATUS_NEW,
        Reply::IS_INITIAL => 1,
        Reply::CREATED_AT => '2021-05-24 16:34:38',
        Reply::AUTHOR_NAME => 'Jack Sprat'
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->registry = $this->objectManager->create(\Magento\Framework\Registry::class);

        $this->reply = $this->getMockBuilder(Reply::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getResource',
            ])
            ->getMock();

        $this->reply->setData($this->replyData);

        $this->resourceMock = $this->getMockBuilder(Resource::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'setAuthorName',
            ])
            ->getMock();
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::getId()
     */
    public function testGetId()
    {
        $this->assertEquals($this->replyData[Reply::REPLY_ID], $this->reply->getId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::setId()
     */
    public function testSetId()
    {
        $testId = 99;
        $this->reply->setId($testId);
        $this->assertEquals($testId, $this->reply->getData(Reply::REPLY_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::getCaseId()
     */
    public function testGetCaseId()
    {
        $this->assertEquals($this->replyData[Reply::CASE_ID], $this->reply->getCaseId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::setCaseId()
     */
    public function testSetCaseId()
    {
        $testCaseId = 99;
        $this->reply->setCaseId($testCaseId);
        $this->assertEquals($testCaseId, $this->reply->getData(Reply::CASE_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::getAuthorId()
     */
    public function testGetAuthorId()
    {
        $this->assertEquals($this->replyData[Reply::AUTHOR_ID], $this->reply->getAuthorId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::setAuthorId()
     */
    public function testSetAuthorId()
    {
        $testAuthorId = 99;
        $this->reply->setAuthorId($testAuthorId);
        $this->assertEquals($testAuthorId, $this->reply->getData(Reply::AUTHOR_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::getAuthorType()
     */
    public function testGetAuthorType()
    {
        $this->assertEquals($this->replyData[Reply::AUTHOR_TYPE], $this->reply->getAuthorType());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::setAuthorType()
     */
    public function testSetAuthorType()
    {
        $testAuthorType = 99;
        $this->reply->setAuthorType($testAuthorType);
        $this->assertEquals($testAuthorType, $this->reply->getData(Reply::AUTHOR_TYPE));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::setAuthorName()
     */
    public function testSetAuthorName()
    {
        $testName = 'John Smith';
        $this->reply->setAuthorName($testName);
        $this->assertNotEquals($testName, $this->reply->getData(Reply::AUTHOR_NAME));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::getAuthorName()
     */
    public function testGetAuthorName()
    {
        $this->assertEquals($this->replyData[Reply::AUTHOR_NAME], $this->reply->getAuthorName());

        $this->reply->unsetData(Reply::AUTHOR_NAME);
        $this->reply->expects($this->once())
            ->method('getResource')
            ->willReturn($this->resourceMock);

        $this->assertNull($this->reply->getAuthorName());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::getReplyText()
     */
    public function testGetReplyText()
    {
        $this->assertEquals($this->replyData[Reply::REPLY_TEXT], $this->reply->getReplyText());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::setReplyText()
     */
    public function testSetReplyText()
    {
        $testReplyText = 'The quick brown fox jumps over the lazy dog.';
        $this->reply->setReplyText($testReplyText);
        $this->assertEquals($testReplyText, $this->reply->getData(Reply::REPLY_TEXT));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::getRemoteIp()
     */
    public function testGetRemoteIp()
    {
        $this->assertEquals($this->replyData[Reply::REMOTE_IP], $this->reply->getRemoteIp());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::setRemoteIp()
     */
    public function testSetRemoteIp()
    {
        $remoteIp = '192.168.1.100';
        $this->reply->setRemoteIp($remoteIp);
        $this->assertEquals($remoteIp, $this->reply->getData(Reply::REMOTE_IP));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::getStatusId()
     */
    public function testGetStatusId()
    {
        $this->assertEquals($this->replyData[Reply::STATUS_ID], $this->reply->getStatusId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::setStatusId()
     */
    public function testSetStatusId()
    {
        $statusId = \Dem\HelpDesk\Model\Source\CaseItem\Status::CASE_STATUS_RESOLVED;
        $this->reply->setStatusId($statusId);
        $this->assertEquals($statusId, $this->reply->getData(Reply::STATUS_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::getIsInitial()
     */
    public function testGetIsInitial()
    {
        $this->assertEquals($this->replyData[Reply::IS_INITIAL], $this->reply->getIsInitial());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::setIsInitial()
     */
    public function testSetIsInitial()
    {
        $testInitial = 0;
        $this->reply->setIsInitial($testInitial);
        $this->assertNotEquals($testInitial, $this->reply->getData(Reply::IS_INITIAL));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::getCreatedAt()
     */
    public function testGetCreatedAt()
    {
        $this->assertEquals($this->replyData[Reply::CREATED_AT], $this->reply->getCreatedAt());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::setCreatedAt()
     */
    public function testSetCreatedAt()
    {
        $createdAt = date('Y-m-d h:m:s');
        $this->reply->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $this->reply->getData(Reply::CREATED_AT));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::getIsAuthorTypeSystem()
     */
    public function testGetIsAuthorTypeSystem()
    {
        $this->assertFalse($this->reply->getIsAuthorTypeSystem());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::getIsAuthorTypeCreator()
     */
    public function testGetIsAuthorTypeCreator()
    {
        $this->assertTrue($this->reply->getIsAuthorTypeCreator());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::getIsAuthorTypeUser()
     */
    public function testGetIsAuthorTypeUser()
    {
        $this->assertFalse($this->reply->getIsAuthorTypeUser());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Reply::getIsAuthorTypeCaseManager()
     */
    public function testGetIsAuthorTypeCaseManager()
    {
        $this->assertFalse($this->reply->getIsAuthorTypeCaseManager());
    }
}
