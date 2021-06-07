<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Api\Data;

use Dem\HelpDesk\Api\Data\ReplyInterface;
use Dem\HelpDesk\Api\Data\FollowerInterface;

/**
 * HelpDesk Api Interface - CaseItem
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
interface CaseItemInterface
{
    const CASE_ID               = 'case_id';
    const WEBSITE_ID            = 'website_id';
    const DEPARTMENT_ID         = 'department_id';
    const PROTECT_CODE          = 'protect_code';
    const CREATOR_CUSTOMER_ID   = 'creator_customer_id';
    const CREATOR_ADMIN_ID      = 'creator_admin_id';
    const CREATOR_NAME          = 'creator_name';
    const CREATOR_EMAIL         = 'creator_email';
    const CREATOR_LAST_READ     = 'creator_last_read';
    const SUBJECT               = 'subject';
    const STATUS_ID             = 'status_id';
    const PRIORITY              = 'priority';
    const REMOTE_IP             = 'remote_ip';
    const HTTP_USER_AGENT       = 'http_user_agent';
    const UPDATER_NAME          = 'updater_name';
    const CREATED_AT            = 'created_at';
    const UPDATED_AT            = 'updated_at';
    const WEBSITE_NAME          = '_website_name';
    const DEPARTMENT_NAME       = '_department_name';
    const CASE_NUMBER           = '_case_number';
    const CASE_MANAGER          = '_case_manager';

    /**
     * Set id
     *
     * @param int $id
     * @return CaseItemInterface
     */
    public function setId($id);

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get case number
     *
     * @return string
     */
    public function getCaseNumber();

    /**
     * Get case manager data
     *
     * @return DataObject
     */
    public function getCaseManager();

    /**
     * Set case manager data
     *
     * @param \Magento\Framework\DataObject $data
     * @return CaseItemInterface
     */
    public function setCaseManager(\Magento\Framework\DataObject $data);

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
     * @return CaseItemInterface
     */
    public function setWebsiteId($websiteId);

    /**
     * Get department id
     *
     * @return int
     */
    public function getDepartmentId();

    /**
     * Set department id
     *
     * @param int $departmentId
     * @return CaseItemInterface
     */
    public function setDepartmentId($departmentId);

    /**
     * Get department name
     *
     * @return string
     */
    public function getDepartmentName();

    /**
     * Set department name
     *
     * @param string $name
     * @return CaseItemInterface
     */
    public function setDepartmentName($name);

    /**
     * Get protect code
     *
     * @return string
     */
    public function getProtectCode();

    /**
     * Set protect code
     *
     * @param string $protectCode
     * @return CaseItemInterface
     */
    public function setProtectCode($protectCode);

    /**
     * Get creator customer id
     *
     * @return int
     */
    public function getCreatorCustomerId();

    /**
     * Set creator customer id
     *
     * @param int $customerId
     * @return CaseItemInterface
     */
    public function setCreatorCustomerId($customerId);

    /**
     * Get creator admin id
     *
     * @return int
     */
    public function getCreatorAdminId();

    /**
     * Set creator admin id
     *
     * @param int $adminId
     * @return CaseItemInterface
     */
    public function setCreatorAdminId($adminId);

    /**
     * Get creator name
     *
     * @return string
     */
    public function getCreatorName();

    /**
     * Set creator name
     *
     * @param string $name
     * @return CaseItemInterface
     */
    public function setCreatorName($name);

    /**
     * Get website name
     *
     * @return string
     */
    public function getWebsiteName();

    /**
     * Set website name
     *
     * @param string $name
     * @return CaseItemInterface
     */
    public function setWebsiteName($name);

    /**
     * Get creator email
     *
     * @return string
     */
    public function getCreatorEmail();

    /**
     * Set creator email
     *
     * @param string $email
     * @return CaseItemInterface
     */
    public function setCreatorEmail($email);

    /**
     * Get creator last read reply id
     *
     * @return int
     */
    public function getCreatorLastRead();

    /**
     * Set creator last read reply id
     *
     * @param int $replyId
     * @return CaseItemInterface
     */
    public function setCreatorLastRead($replyId);

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject();

    /**
     * Set subject
     *
     * @param string $subject
     * @return CaseItemInterface
     */
    public function setSubject($subject);

    /**
     * Get status id
     *
     * @return int
     */
    public function getStatusId();

    /**
     * Set status id
     *
     * @param int $statusId
     * @return CaseItemInterface
     */
    public function setStatusId($statusId);

    /**
     * Get priority
     *
     * @return int
     */
    public function getPriority();

    /**
     * Set priority
     *
     * @param int $priority
     * @return CaseItemInterface
     */
    public function setPriority($priority);

    /**
     * Get remote ip address
     *
     * @return string
     */
    public function getRemoteIp();

    /**
     * Set remote ip address
     *
     * @param string $remoteIp
     * @return CaseItemInterface
     */
    public function setRemoteIp($remoteIp);

    /**
     * Get http user agent
     *
     * @return string
     */
    public function getHttpUserAgent();

    /**
     * Set http user agent
     *
     * @param string $userAgent
     * @return CaseItemInterface
     */
    public function setHttpUserAgent($userAgent);

    /**
     * Get name of last updater
     *
     * @return string
     */
    public function getUpdaterName();

    /**
     * Set name of last updater
     *
     * @param string $userName
     * @return CaseItemInterface
     */
    public function setUpdaterName($userName);

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
     * @return CaseItemInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     * set updated at
     *
     * @param $updatedAt
     * @return CaseItemInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get repliesToSave array
     *
     * @return array
     */
    public function getRepliesToSave();

    /**
     * Add data to repliesToSave array
     *
     * @param ReplyInterface $reply
     * @return CaseItemInterface
     */
    public function addReplyToSave(ReplyInterface $reply);

    /**
     * Reset replies
     *
     * @return CaseItemInterface
     */
    public function clearRepliesToSave();

    /**
     * Get followers array
     *
     * @return array
     */
    public function getFollowersToSave();

    /**
     * Add new follower or flag existing isDeleted
     *
     * @param FollowerInterface $follower
     * @param bool $delete Flag existing for removal
     * @return CaseItemInterface
     */
    public function addFollowerToSave(FollowerInterface $follower, $delete = false);

    /**
     * Reset followers
     *
     * @return CaseItemInterface
     */
    public function clearFollowersToSave();
}
