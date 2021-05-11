<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Model\AbstractModel;
use Dem\HelpDesk\Api\Data\CaseItemInterface;
use Dem\HelpDesk\Api\Data\ReplyInterface;
use Dem\HelpDesk\Api\Data\FollowerInterface;

/**
 * HelpDesk Model - Case
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class CaseItem extends AbstractModel implements CaseItemInterface
{
    const CURRENT_KEY = 'current_case';
    const CACHE_TAG = 'helpdesk_case';
    const EVENT_PREFIX = 'helpdesk_case';

    /**
     * @var string
     */
    protected $_eventPrefix = self::EVENT_PREFIX;
    protected $_eventObject = self::EVENT_PREFIX;

    /**
     * @var array
     */
    protected $repliesToSave = [];
    protected $followersToSave = [];

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Dem\HelpDesk\Model\ResourceModel\CaseItem::class);
    }

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(CaseItemInterface::CASE_ID);
    }

    /**
     * Get case number
     *
     * @return string
     */
    public function getCaseNumber()
    {
        return $this->getData(CaseItemInterface::CASE_NUMBER);
    }

    /**
     * Get website id
     *
     * @return int
     */
    public function getWebsiteId()
    {
        return $this->getData(CaseItemInterface::WEBSITE_ID);
    }

    /**
     * Set website id
     *
     * @param int $websiteId
     * @return CaseItemInterface
     */
    public function setWebsiteId($websiteId)
    {
        return $this->setData(CaseItemInterface::WEBSITE_ID, $websiteId);
    }

    /**
     * Get department id
     *
     * @return int
     */
    public function getDepartmentId()
    {
        return $this->getData(CaseItemInterface::DEPARTMENT_ID);
    }

    /**
     * Set department id
     *
     * @param int $departmentId
     * @return CaseItemInterface
     */
    public function setDepartmentId($departmentId)
    {
        return $this->setData(CaseItemInterface::DEPARTMENT_ID, $departmentId);
    }

    /**
     * Get department name
     *
     * @return string
     */
    public function getDepartmentName()
    {
        return '';
    }

    /**
     * Get protect code
     *
     * @return string
     */
    public function getProtectCode()
    {
        return $this->getData(CaseItemInterface::PROTECT_CODE);
    }

    /**
     * Set protect code
     *
     * @param string $protectCode
     * @return CaseItemInterface
     */
    public function setProtectCode($protectCode)
    {
        return $this->setData(CaseItemInterface::PROTECT_CODE, $protectCode);
    }

    /**
     * Get creator customer id
     *
     * @return int
     */
    public function getCreatorCustomerId()
    {
        return $this->getData(CaseItemInterface::CREATOR_CUSTOMER_ID);
    }

    /**
     * Set creator customer id
     *
     * @param int $customerId
     * @return CaseItemInterface
     */
    public function setCreatorCustomerId($customerId)
    {
        return $this->setData(CaseItemInterface::CREATOR_CUSTOMER_ID, $customerId);
    }

    /**
     * Get creator admin id
     *
     * @return int
     */
    public function getCreatorAdminId()
    {
        return $this->getData(CaseItemInterface::CREATOR_ADMIN_ID);
    }

    /**
     * Set creator admin id
     *
     * @param int $adminId
     * @return CaseItemInterface
     */
    public function setCreatorAdminId($adminId)
    {
        return $this->setData(CaseItemInterface::CREATOR_ADMIN_ID, $adminId);
    }

    /**
     * Get creator name
     *
     * @return string
     */
    public function getCreatorName()
    {
        return $this->getData(CaseItemInterface::CREATOR_NAME);
    }

    /**
     * Set creator name
     *
     * @param string $name
     * @return CaseItemInterface
     */
    public function setCreatorName($name)
    {
        return $this->setData(CaseItemInterface::CREATOR_NAME, $name);
    }

    /**
     * Get creator email
     *
     * @return string
     */
    public function getCreatorEmail()
    {
        return $this->getData(CaseItemInterface::CREATOR_EMAIL);
    }

    /**
     * Set creator email
     *
     * @param string $email
     * @return CaseItemInterface
     */
    public function setCreatorEmail($email)
    {
        return $this->setData(CaseItemInterface::CREATOR_EMAIL, $email);
    }

    /**
     * Get creator last read reply id
     *
     * @return int
     */
    public function getCreatorLastRead()
    {
        return $this->getData(CaseItemInterface::CREATOR_LAST_READ);
    }

    /**
     * Set creator last read reply id
     *
     * @param int $replyId
     * @return CaseItemInterface
     */
    public function setCreatorLastRead($replyId)
    {
        return $this->setData(CaseItemInterface::CREATOR_LAST_READ, $replyId);
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->getData(CaseItemInterface::SUBJECT);
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return CaseItemInterface
     */
    public function setSubject($subject)
    {
        return $this->setData(CaseItemInterface::SUBJECT, $subject);
    }

    /**
     * Get status id
     *
     * @return int
     */
    public function getStatusId()
    {
        return $this->getData(CaseItemInterface::STATUS_ID);
    }

    /**
     * Set status id
     *
     * @param int $statusId
     * @return CaseItemInterface
     */
    public function setStatusId($statusId)
    {
        return $this->setData(CaseItemInterface::STATUS_ID, $statusId);
    }

    /**
     * Get priority
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->getData(CaseItemInterface::PRIORITY);
    }

    /**
     * Set priority
     *
     * @param int $priority
     * @return CaseItemInterface
     */
    public function setPriority($priority)
    {
        return $this->setData(CaseItemInterface::PRIORITY, $priority);
    }

    /**
     * Get remote ip address
     *
     * @return string
     */
    public function getRemoteIp()
    {
        return $this->getData(CaseItemInterface::REMOTE_IP);
    }

    /**
     * Set remote ip address
     *
     * @param string $remoteIp
     * @return CaseItemInterface
     */
    public function setRemoteIp($remoteIp)
    {
        return $this->setData(CaseItemInterface::REMOTE_IP, $remoteIp);
    }

    /**
     * Get http user agent
     *
     * @return string
     */
    public function getHttpUserAgent()
    {
        return $this->getData(CaseItemInterface::HTTP_USER_AGENT);
    }

    /**
     * Set http user agent
     *
     * @param string $userAgent
     * @return CaseItemInterface
     */
    public function setHttpUserAgent($userAgent)
    {
        return $this->setData(CaseItemInterface::HTTP_USER_AGENT, $userAgent);
    }

    /**
     * Get name of last updated user
     *
     * @return string
     */
    public function getUpdatedBy()
    {
        return $this->getData(CaseItemInterface::UPDATED_BY);
    }

    /**
     * Set name of last updated user
     *
     * @param string $userName
     * @return CaseItemInterface
     */
    public function setUpdatedBy($userName)
    {
        return $this->setData(CaseItemInterface::UPDATED_BY, $userName);
    }

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(CaseItemInterface::CREATED_AT);
    }

    /**
     * set created at
     *
     * @param $createdAt
     * @return CaseItemInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(CaseItemInterface::CREATED_AT, $createdAt);
    }

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(CaseItemInterface::UPDATED_AT);
    }

    /**
     * set updated at
     *
     * @param $updatedAt
     * @return CaseItemInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(CaseItemInterface::UPDATED_AT, $updatedAt);
    }

    /**************************************************************************/
    /**************************************************************************/

    /**
     * Get repliesToSave array
     *
     * @return array
     */
    public function getRepliesToSave()
    {
        return $this->repliesToSave;
    }

    /**
     * Add data to repliesToSave array
     *
     * @param ReplyInterface $reply
     * @return CaseItemInterface
     */
    public function addReplyToSave(ReplyInterface $reply)
    {
        $this->repliesToSave[] = $reply;

        // Always set this flag to force triggering before/afterSave()
        $this->_hasDataChanges = true;

        return $this;
    }

    /**
     * Reset replies
     *
     * @return CaseItemInterface
     */
    public function clearRepliesToSave()
    {
        $this->repliesToSave = [];
        return $this;
    }

    /**************************************************************************/
    /**************************************************************************/

    /**
     * Get followers array
     *
     * @return array
     */
    public function getFollowersToSave()
    {
        return $this->followersToSave;
    }

    /**
     * Add new follower or flag existing isDeleted
     *
     * @param FollowerInterface $follower
     * @param bool $delete Flag existing for removal
     * @return CaseItemInterface
     */
    public function addFollowerToSave(FollowerInterface $follower, $delete = false)
    {
        $follower->isDeleted($delete);
        $this->followersToSave[] = $follower;

        // Always set this flag to force triggering before/afterSave()
        $this->_hasDataChanges = true;

        return $this;
    }

    /**
     * Reset followers
     *
     * @return CaseItemInterface
     */
    public function clearFollowersToSave()
    {
        $this->followersToSave = [];
        return $this;
    }
}
