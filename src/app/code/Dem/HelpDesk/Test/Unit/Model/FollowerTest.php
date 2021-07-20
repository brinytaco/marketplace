<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model;

use Dem\HelpDesk\Model\Follower;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Registry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Model Follower
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class FollowerTest extends TestCase
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
     * @var MockObject|Follower
     */
    protected $follower;

    /**
     * Test Department data
     * @var []
     */
    protected $followerData = [
        Follower::FOLLOWER_ID => 1,
        Follower::CASE_ID => 1,
        Follower::USER_ID => 1,
        Follower::LAST_READ => 1,
        Follower::CREATED_AT => '2021-05-24 16:34:38',
        Follower::UPDATED_AT => '2021-05-24 16:34:38'
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->registry = $this->objectManager->create(\Magento\Framework\Registry::class);
        $this->follower = $this->objectManager->create(Follower::class);

        $this->follower->setData($this->followerData);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Follower::getId()
     */
    public function testGetId()
    {
        $this->assertEquals($this->followerData[Follower::FOLLOWER_ID], $this->follower->getId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Follower::setId()
     */
    public function testSetId()
    {
        $testId = 99;
        $this->follower->setId($testId);
        $this->assertEquals($testId, $this->follower->getData(Follower::FOLLOWER_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Follower::getCaseId()
     */
    public function testGetCaseId()
    {
        $this->assertEquals($this->followerData[Follower::CASE_ID], $this->follower->getCaseId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Follower::setCaseId()
     */
    public function testSetCaseId()
    {
        $testCaseId = 99;
        $this->follower->setCaseId($testCaseId);
        $this->assertEquals($testCaseId, $this->follower->getData(Follower::CASE_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Follower::getUserId()
     */
    public function testGetUserId()
    {
        $this->assertEquals($this->followerData[Follower::USER_ID], $this->follower->getUserId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Follower::setUserId()
     */
    public function testSetUserId()
    {
        $testUserId = 99;
        $this->follower->setUserId($testUserId);
        $this->assertEquals($testUserId, $this->follower->getData(Follower::USER_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Follower::getLastRead()
     */
    public function testGetLastRead()
    {
        $this->assertEquals($this->followerData[Follower::LAST_READ], $this->follower->getLastRead());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Follower::setLastRead()
     */
    public function testSetLastRead()
    {
        $testLastRead = 99;
        $this->follower->setLastRead($testLastRead);
        $this->assertEquals($testLastRead, $this->follower->getData(Follower::LAST_READ));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Follower::getCreatedAt()
     */
    public function testGetCreatedAt()
    {
        $this->assertEquals($this->followerData[Follower::CREATED_AT], $this->follower->getCreatedAt());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Follower::setCreatedAt()
     */
    public function testSetCreatedAt()
    {
        $createdAt = date('Y-m-d h:m:s');
        $this->follower->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $this->follower->getData(Follower::CREATED_AT));
    }

    /**
     * @covers \Dem\HelpDesk\Model\Follower::getUpdatedAt()
     */
    public function testGetUpdatedAt()
    {
        $this->assertEquals($this->followerData[Follower::UPDATED_AT], $this->follower->getUpdatedAt());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Follower::setUpdatedAt()
     */
    public function testSetUpdatedAt()
    {
        $updatedAt = date('Y-m-d h:m:s');
        $this->follower->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $this->follower->getData(Follower::UPDATED_AT));
    }
}
