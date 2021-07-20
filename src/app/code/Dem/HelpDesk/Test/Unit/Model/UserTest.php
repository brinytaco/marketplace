<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Test\Unit\Model;

use Dem\HelpDesk\Model\User;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Registry;
use PHPUnit\Framework\TestCase;

/**
 * HelpDesk Unit Test - Model User
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class UserTest extends TestCase
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
     * @var MockObject|User
     */
    protected $user;

    /**
     * Test Department data
     * @var []
     */
    protected $userData = [
        User::USER_ID => 1,
        User::WEBSITE_ID => 0,
        User::CUSTOMER_ID => null,
        User::ADMIN_ID => 1,
        User::EMAIL => 'jack@sprat.com',
        User::NAME => 'Jack Sprat',
        User::SESSION_ID => '925ad0c924f7f9ef40f5d8bdb0a59c3fabdee29d',
        User::LAST_ACCESSED => '2021-05-24 16:34:38',
        User::CREATED_AT => '2021-05-24 16:34:38'
    ];

    protected function setUp(): void
    {
        $this->objectManager = ObjectManager::getInstance();

        $this->registry = $this->objectManager->create(\Magento\Framework\Registry::class);
        $this->user = $this->objectManager->create(User::class);

        $this->user->setData($this->userData);
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::getId()
     */
    public function testGetId()
    {
        $this->assertEquals($this->userData[User::USER_ID], $this->user->getId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::setId()
     */
    public function testSetId()
    {
        $testId = 99;
        $this->user->setId($testId);
        $this->assertEquals($testId, $this->user->getData(User::USER_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::getWebsiteId()
     */
    public function testGetWebsiteId()
    {
        $this->assertEquals($this->userData[User::WEBSITE_ID], $this->user->getWebsiteId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::setWebsiteId()
     */
    public function testSetWebsiteId()
    {
        $testWebsiteId = 99;
        $this->user->setWebsiteId($testWebsiteId);
        $this->assertEquals($testWebsiteId, $this->user->getData(User::WEBSITE_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::getCustomerId()
     */
    public function testGetCustomerId()
    {
        $this->assertEquals($this->userData[User::CUSTOMER_ID], $this->user->getCustomerId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::setCustomerId()
     */
    public function testSetCustomerId()
    {
        $testCustomerId = 99;
        $this->user->setCustomerId($testCustomerId);
        $this->assertEquals($testCustomerId, $this->user->getData(User::CUSTOMER_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::getAdminId()
     */
    public function testGetAdminId()
    {
        $this->assertEquals($this->userData[User::ADMIN_ID], $this->user->getAdminId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::setAdminId()
     */
    public function testSetAdminId()
    {
        $testAdminId = 99;
        $this->user->setAdminId($testAdminId);
        $this->assertEquals($testAdminId, $this->user->getData(User::ADMIN_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::getName()
     */
    public function testGetName()
    {
        $this->assertEquals($this->userData[User::NAME], $this->user->getName());
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::setName()
     */
    public function testSetName()
    {
        $testName = 99;
        $this->user->setName($testName);
        $this->assertEquals($testName, $this->user->getData(User::NAME));
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::getEmail()
     */
    public function testGetEmail()
    {
        $this->assertEquals($this->userData[User::EMAIL], $this->user->getEmail());
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::setEmail()
     */
    public function testSetEmail()
    {
        $testEmail = 99;
        $this->user->setEmail($testEmail);
        $this->assertEquals($testEmail, $this->user->getData(User::EMAIL));
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::getSessionId()
     */
    public function testGetSessionId()
    {
        $this->assertEquals($this->userData[User::SESSION_ID], $this->user->getSessionId());
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::setSessionId()
     */
    public function testSetSessionId()
    {
        $testSessionId = '78e370a0fb88f375bd3ce4f7f81f4c266fbe5b0d';
        $this->user->setSessionId($testSessionId);
        $this->assertEquals($testSessionId, $this->user->getData(User::SESSION_ID));
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::getLastAccessed()
     */
    public function testGetLastAccessed()
    {
        $this->assertEquals($this->userData[User::LAST_ACCESSED], $this->user->getLastAccessed());
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::setLastAccessed()
     */
    public function testSetLastAccessed()
    {
        $testLastAccessed = date('Y-m-d h:m:s');
        $this->user->setLastAccessed($testLastAccessed);
        $this->assertEquals($testLastAccessed, $this->user->getData(User::LAST_ACCESSED));
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::getCreatedAt()
     */
    public function testGetCreatedAt()
    {
        $this->assertEquals($this->userData[User::CREATED_AT], $this->user->getCreatedAt());
    }

    /**
     * @covers \Dem\HelpDesk\Model\User::setCreatedAt()
     */
    public function testSetCreatedAt()
    {
        $createdAt = date('Y-m-d h:m:s');
        $this->user->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $this->user->getData(User::CREATED_AT));
    }
}
