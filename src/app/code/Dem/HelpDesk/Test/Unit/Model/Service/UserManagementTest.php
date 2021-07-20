<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model\Service;

use Dem\HelpDesk\Model\Service\UserManagement;
use Dem\HelpDesk\Model\User;
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
class UserManagementTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MockObject|UserManagement
     */
    protected $modelManager;

    /**
     *
     * @var MockObject|User
     */
    protected $user;

    /**
     * Test User data
     * @var []
     */
    protected $userData = [
        User::WEBSITE_ID => 1,
        User::EMAIL => 1,
        User::NAME => 'Jack Sprat'
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->modelManager = $this->objectManager->create(UserManagement::class);
    }

    /**
     * Covers void return value + 1st Exception block
     *
     * @covers \Dem\HelpDesk\Model\Service\UserManagement::validate()
     */
    public function testValidate1()
    {
        $this->assertNull($this->modelManager->validate($this->userData));

        $this->userData[User::WEBSITE_ID] = '';
        $this->expectExceptionObject(
            new \Dem\HelpDesk\Exception(__('The %1 `%2` cannot be empty', 'user', User::WEBSITE_ID))
        );
        $this->modelManager->validate($this->userData);
    }

    /**
     * Covers 2nd Exception block
     *
     * @covers \Dem\HelpDesk\Model\Service\UserManagement::validate()
     */
    public function testValidate2()
    {
        unset($this->userData[User::WEBSITE_ID]);
        $this->expectExceptionObject(
            new \Dem\HelpDesk\Exception(__('The %1 `%2` cannot be empty', 'user', User::WEBSITE_ID))
        );
        $this->modelManager->validate($this->userData);
    }

    /**
     * Covers return of empty required array
     *
     * @covers \Dem\HelpDesk\Model\Service\UserManagement::validate()
     */
    public function testValidate3()
    {
        /** @var UserManagement|MockObject $modelManagerMock */
        $modelManagerMock = $this->getMockBuilder(UserManagement::class)
            ->setMethods([
                'getRequiredFields'
            ])
            ->getMock();

        $modelManagerMock->expects($this->once())
            ->method('getRequiredFields')
            ->willReturn([]);

        $modelManagerMock->validate($this->userData);
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\UserManagement::getRequiredFields()
     */
    public function testGetRequiredFields()
    {
        $fields = [
            User::WEBSITE_ID,
            User::EMAIL,
            User::NAME
        ];

        $this->assertSame($fields, $this->modelManager->getRequiredFields());
    }

    /**
     * @covers \Dem\HelpDesk\Model\Service\UserManagement::getEditableFields()
     */
    public function testGetEditableFields()
    {
        $this->assertSame([], $this->modelManager->getEditableFields());
    }
}
