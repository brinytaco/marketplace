<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Dem\HelpDesk\Model\ResourceModel\User as Resource;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\App\ObjectManager;

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
class User extends AbstractModel
{
    const EVENT_PREFIX = 'helpdesk_user';

    const USER_ID               = 'user_id';
    const WEBSITE_ID            = 'website_id';
    const CUSTOMER_ID           = 'customer_id';
    const ADMIN_ID              = 'admin_id';
    const EMAIL                 = 'email';
    const NAME                  = 'name';
    const SESSION_ID            = 'session_id';
    const LAST_ACCESSED         = 'last_accessed';
    const CREATED_AT            = 'created_at';

    /**
     * @var string
     */
    protected $_eventPrefix = self::EVENT_PREFIX;
    protected $_eventObject = self::EVENT_PREFIX;

    /**
     * @return void
     * @codeCoverageIgnore
     */
    protected function _construct()
    {
        $this->_init(Resource::class);
    }

    /**
     * Get resource instance
     *
     * Added here for proper PHPDoc return of Resource class
     *
     * @throws LocalizedException
     * @return \Dem\HelpDesk\Model\ResourceModel\User
     * @codeCoverageIgnore
     */
    public function getResource()
    {
        return parent::getResource();
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::USER_ID);
    }

    /**
     * Set id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData(self::USER_ID, $id);
    }

    /**
     * Get website id
     *
     * @return int
     */
    public function getWebsiteId()
    {
        return $this->getData(self::WEBSITE_ID);
    }

    /**
     * Set website id
     *
     * @param int $websiteId
     * @return $this
     */
    public function setWebsiteId($websiteId)
    {
        return $this->setData(self::WEBSITE_ID, $websiteId);
    }

    /**
     * Get customer id
     *
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Set customer id
     *
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Get admin id
     *
     * @return string|null
     */
    public function getAdminId()
    {
        return $this->getData(self::ADMIN_ID);
    }

    /**
     * Set admin id
     *
     * @param int $adminId
     * @return $this
     */
    public function setAdminId($adminId)
    {
        return $this->setData(self::ADMIN_ID, $adminId);
    }

    /**
     * Get user email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * Set user email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * Get user name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Set user name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get session id
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->getData(self::SESSION_ID);
    }

    /**
     * set session id
     *
     * @param $sessionId
     * @return $this
     */
    public function setSessionId($sessionId)
    {
        return $this->setData(self::SESSION_ID, $sessionId);
    }

    /**
     * Get last accessed
     *
     * @return string
     */
    public function getLastAccessed()
    {
        return $this->getData(self::LAST_ACCESSED);
    }

    /**
     * set last accessed
     *
     * @param $lastAccessed
     * @return $this
     */
    public function setLastAccessed($lastAccessed)
    {
        return $this->setData(self::LAST_ACCESSED, $lastAccessed);
    }

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * set created at
     *
     * @param $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

}
