<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Model\AbstractModel;
use Dem\HelpDesk\Api\Data\UserInterface;

/**
 * HelpDesk Model - User
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class User extends AbstractModel implements UserInterface
{
    const EVENT_PREFIX = 'helpdesk_user';

    /**
     * @var string
     */
    protected $_eventPrefix = self::EVENT_PREFIX;
    protected $_eventObject = self::EVENT_PREFIX;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Dem\HelpDesk\Model\ResourceModel\User::class);
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(UserInterface::USER_ID);
    }

    /**
     * Get website id
     *
     * @return int
     */
    public function getWebsiteId()
    {
        return $this->getData(UserInterface::WEBSITE_ID);
    }

    /**
     * Set website id
     *
     * @param int $websiteId
     * @return UserInterface
     */
    public function setWebsiteId($websiteId)
    {
        return $this->setData(UserInterface::WEBSITE_ID, $websiteId);
    }

    /**
     * Get customer id
     *
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->getData(UserInterface::CUSTOMER_ID);
    }

    /**
     * Set customer id
     *
     * @param int $customerId
     * @return UserInterface
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(UserInterface::CUSTOMER_ID, $customerId);
    }

    /**
     * Get admin id
     *
     * @return string|null
     */
    public function getAdminId()
    {
        return $this->getData(UserInterface::ADMIN_ID);
    }

    /**
     * Set admin id
     *
     * @param int $adminId
     * @return UserInterface
     */
    public function setAdminId($adminId)
    {
        return $this->setData(UserInterface::ADMIN_ID, $adminId);
    }

    /**
     * Get user email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getData(UserInterface::EMAIL);
    }

    /**
     * Set user email
     *
     * @param string $email
     * @return UserInterface
     */
    public function setEmail($email)
    {
        return $this->setData(UserInterface::EMAIL, $email);
    }

    /**
     * Get user name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(UserInterface::NAME);
    }

    /**
     * Set user name
     *
     * @param string $name
     * @return UserInterface
     */
    public function setName($name)
    {
        return $this->setData(UserInterface::NAME, $name);
    }

    /**
     * Get session id
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->getData(UserInterface::SESSION_ID);
    }

    /**
     * set session id
     *
     * @param $sessionId
     * @return UserInterface
     */
    public function setSessionId($sessionId)
    {
        return $this->setData(UserInterface::SESSION_ID, $sessionId);
    }

    /**
     * Get last accessed
     *
     * @return string
     */
    public function getLastAccessed()
    {
        return $this->getData(UserInterface::LAST_ACCESSED);
    }

    /**
     * set last accessed
     *
     * @param $lastAccessed
     * @return UserInterface
     */
    public function setLastAccessed($lastAccessed)
    {
        return $this->setData(UserInterface::LAST_ACCESSED, $lastAccessed);
    }

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(UserInterface::CREATED_AT);
    }

    /**
     * set created at
     *
     * @param $createdAt
     * @return UserInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(UserInterface::CREATED_AT, $createdAt);
    }

}
