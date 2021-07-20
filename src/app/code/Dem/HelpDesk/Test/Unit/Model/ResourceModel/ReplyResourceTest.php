<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model\ResourceModel;

use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\ResourceModel\Reply as Resource;
use Dem\HelpDesk\Model\User;
use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - ResourceModel Reply
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class ReplyResourceTest extends TestCase
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
     * @var MockObject|Reply
     */
    protected $reply;

    /**
     * @var MockObject|User
     */
    protected $user;

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->resourceMock = $this->getMockBuilder(Resource::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getUserById'
            ])
            ->getMock();

        $this->reply = $this->objectManager->create(Reply::class);
        $this->user = $this->objectManager->create(User::class);
    }

    /**
     * @covers \Dem\HelpDesk\Model\ResourceModel\Reply::setAuthorName()
     */
    public function testSetAuthorName()
    {
        $testAuthorName = 'Jack Sprat';
        $this->reply->setAuthorId(1);
        $this->user->setName($testAuthorName);

        $this->resourceMock->expects($this->once())
            ->method('getUserById')
            ->willReturn($this->user);

        $this->assertInstanceOf(Resource::class, $this->resourceMock->setAuthorName($this->reply));
        $this->assertEquals($testAuthorName, $this->reply->getData(Reply::AUTHOR_NAME));
    }
}
