<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model\Service;

use Dem\HelpDesk\Model\CaseItem;
use Dem\HelpDesk\Model\Follower;
use Dem\HelpDesk\Model\Service\FollowerManagement;
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
class FollowerManagementTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|FollowerManagement
     */
    protected $modelManager;

    /**
     *
     * @var Follower
     */
    protected $follower;

    /**
     * Test Follower data
     * @var []
     */
    protected $followerData = [
        Follower::CASE_ID => 1,
        Follower::USER_ID => 1
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->modelManager = $this->objectManager->create(FollowerManagement::class);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\FollowerManagement::createFollower()
     */
    public function testCreateFollower()
    {
        /** @var Follower|MockObject $follower */
        /** @var CaseItem|MockObject $caseItem */
        $follower = $this->objectManager->create(Follower::class);
        $caseItem = $this->objectManager->create(CaseItem::class);
        $caseItem->addData([$this->followerData[Follower::CASE_ID]]);
        $userId = $this->followerData[Follower::USER_ID];

        $newFollower = $this->modelManager->createFollower($follower, $caseItem, $userId);
        $this->assertInstanceOf(Follower::class, $newFollower);
        $this->assertEquals($caseItem->getId(), $newFollower->getCaseId());
        $this->assertEquals($userId, $newFollower->getUserId());
    }

    /**
     * Covers void return value + 1st Exception block
     *
     * @covers \Dem\HelpDesk\Model\Service\FollowerManagement::validate()
     */
    public function testValidate1()
    {
        $this->assertNull($this->modelManager->validate($this->followerData));

        $this->followerData[Follower::CASE_ID] = '';
        $this->expectExceptionObject(
            new \Dem\HelpDesk\Exception(__('The %1 `%2` cannot be empty', 'follower', Follower::CASE_ID))
        );
        $this->modelManager->validate($this->followerData);
    }

    /**
     * Covers 2nd Exception block
     *
     * @covers \Dem\HelpDesk\Model\Service\FollowerManagement::validate()
     */
    public function testValidate2()
    {
        unset($this->followerData[Follower::CASE_ID]);
        $this->expectExceptionObject(
            new \Dem\HelpDesk\Exception(__('The %1 `%2` cannot be empty','follower', Follower::CASE_ID))
        );
        $this->modelManager->validate($this->followerData);
    }

    /**
     * Covers return of empty required array
     *
     * @covers \Dem\HelpDesk\Model\Service\FollowerManagement::validate()
     */
    public function testValidate3()
    {
        /** @var FollowerManagement|MockObject $modelManagerMock */
        $modelManagerMock = $this->getMockBuilder(FollowerManagement::class)
            ->setMethods([
                'getRequiredFields'
            ])
            ->getMock();

        $modelManagerMock->expects($this->once())
            ->method('getRequiredFields')
            ->willReturn([]);

        $modelManagerMock->validate($this->followerData);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\FollowerManagement::getRequiredFields()
     */
    public function testGetRequiredFields()
    {
        $fields = [
            Follower::CASE_ID,
            Follower::USER_ID
        ];

        $this->assertSame($fields, $this->modelManager->getRequiredFields());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\FollowerManagement::getEditableFields()
     */
    public function testGetEditableFields()
    {
        $this->assertSame([], $this->modelManager->getEditableFields());
    }
}
