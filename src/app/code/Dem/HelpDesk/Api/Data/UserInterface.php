<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Api\Data;

/**
 * HelpDesk Api Interface - User
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
interface UserInterface
{
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
     * Get ID
     *
     * @return int
     */
    public function getId();

    /**
     * Get website id
     *
     * @return int
     */
    public function getWebsiteId();

    /**
     * Set website id
     *
     * @param int $websiteId
     * @return UserInterface
     */
    public function setWebsiteId($websiteId);

    /**
     * Get customer id
     *
     * @return string|null
     */
    public function getCustomerId();

    /**
     * Set customer id
     *
     * @param int $customerId
     * @return UserInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get admin id
     *
     * @return string|null
     */
    public function getAdminId();

    /**
     * Set admin id
     *
     * @param int $adminId
     * @return UserInterface
     */
    public function setAdminId($adminId);

    /**
     * Get user email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Set user email
     *
     * @param string $email
     * @return UserInterface
     */
    public function setEmail($email);

    /**
     * Get user name
     *
     * @return string
     */
    public function getName();

    /**
     * Set user name
     *
     * @param string $name
     * @return UserInterface
     */
    public function setName($name);

    /**
     * Get session id
     *
     * @return string
     */
    public function getSessionId();

    /**
     * set session id
     *
     * @param $sessionId
     * @return UserInterface
     */
    public function setSessionId($sessionId);

    /**
     * Get last accessed
     *
     * @return string
     */
    public function getLastAccessed();

    /**
     * set last accessed
     *
     * @param $lastAccessed
     * @return UserInterface
     */
    public function setLastAccessed($lastAccessed);

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * set created at
     *
     * @param $createdAt
     * @return UserInterface
     */
    public function setCreatedAt($createdAt);
}
