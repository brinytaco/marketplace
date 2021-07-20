<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Model\AbstractModel;
use Dem\HelpDesk\Model\ResourceModel\CaseItem as Resource;
use Dem\HelpDesk\Model\Department;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\Follower;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Model\Context;
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
class CaseItem extends AbstractModel
{
    const CACHE_TAG             = 'helpdesk_case';
    const EVENT_PREFIX          = 'helpdesk_case';
    const CURRENT_KEY           = 'current_case';
    const INITIAL_REPLY_KEY     = 'initial_reply';

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
     * @var string
     */
    protected $_eventPrefix = self::EVENT_PREFIX;
    protected $_eventObject = self::EVENT_PREFIX;

    /**
     * @var \Dem\HelpDesk\Model\Reply[]
     */
    protected $repliesToSave = [];

    /**
     * @var SearchResultsInterface
     */
    protected $replies;

    /**
     *
     * @var \Dem\HelpDesk\Model\Follower[]
     */
    protected $followersToSave = [];

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
     * @return \Dem\HelpDesk\Model\ResourceModel\CaseItem
     * @codeCoverageIgnore
     */
    public function getResource()
    {
        return parent::getResource();
    }

    /**
     * Set id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData(self::CASE_ID, $id);
    }

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::CASE_ID);
    }

    /**
     * Set case number
     *
     * This value is set dynamically on load,
     * it cannot be set here
     *
     * @param string $caseNumber
     * @return $this
     */
    public function setCaseNumber($caseNumber)
    {
        return $this;
    }

    /**
     * Get case number
     *
     * @return string
     */
    public function getCaseNumber()
    {
        if (!$this->hasData(self::CASE_NUMBER)) {
            $this->getResource()->setCaseNumber($this);
        }
        return $this->getData(self::CASE_NUMBER);
    }

    /**
     * Get case manager data
     *
     * @return User
     */
    public function getCaseManager()
    {
        if (!$this->hasData(self::CASE_MANAGER)) {
            $this->getResource()->setCaseManager($this);
        }
        return $this->getData(self::CASE_MANAGER);
    }

    /**
     * Set case manager data
     *
     * This value is set dynamically on load,
     * it cannot be set here
     *
     * @param \Magento\Framework\DataObject $data
     * @return $this
     */
    public function setCaseManager(\Magento\Framework\DataObject $data)
    {
        return $this;
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
     * Get department id
     *
     * @return int
     */
    public function getDepartmentId()
    {
        return $this->getData(self::DEPARTMENT_ID);
    }

    /**
     * Set department id
     *
     * @param int $departmentId
     * @return $this
     */
    public function setDepartmentId($departmentId)
    {
        return $this->setData(self::DEPARTMENT_ID, $departmentId);
    }

    /**
     * Get department name
     *
     * @return string
     */
    public function getDepartmentName()
    {
        return $this->getData(self::DEPARTMENT_NAME);
    }

    /**
     * Set department name
     *
     * This value is set dynamically on load,
     * it cannot be set here
     *
     * @param string $name
     * @return $this
     */
    public function setDepartmentName($name)
    {
        return $this;
    }


    /**
     * Get website name
     *
     * @return string
     */
    public function getWebsiteName()
    {
        return $this->getData(self::WEBSITE_NAME);
    }

    /**
     * Set website name
     *
     * This value is set dynamically on load,
     * it cannot be set here
     *
     * @param string $name
     * @return $this
     */
    public function setWebsiteName($name)
    {
        return $this;
    }


    /**
     * Get protect code
     *
     * @return string
     */
    public function getProtectCode()
    {
        return $this->getData(self::PROTECT_CODE);
    }

    /**
     * Set protect code
     *
     * @param string $protectCode
     * @return $this
     */
    public function setProtectCode($protectCode)
    {
        return $this->setData(self::PROTECT_CODE, $protectCode);
    }

    /**
     * Get creator customer id
     *
     * @return int
     */
    public function getCreatorCustomerId()
    {
        return $this->getData(self::CREATOR_CUSTOMER_ID);
    }

    /**
     * Set creator customer id
     *
     * @param int $customerId
     * @return $this
     */
    public function setCreatorCustomerId($customerId)
    {
        return $this->setData(self::CREATOR_CUSTOMER_ID, $customerId);
    }

    /**
     * Get creator admin id
     *
     * @return int
     */
    public function getCreatorAdminId()
    {
        return $this->getData(self::CREATOR_ADMIN_ID);
    }

    /**
     * Set creator admin id
     *
     * @param int $adminId
     * @return $this
     */
    public function setCreatorAdminId($adminId)
    {
        return $this->setData(self::CREATOR_ADMIN_ID, $adminId);
    }

    /**
     * Get creator name
     *
     * @return string
     */
    public function getCreatorName()
    {
        return $this->getData(self::CREATOR_NAME);
    }

    /**
     * Set creator name
     *
     * @param string $name
     * @return $this
     */
    public function setCreatorName($name)
    {
        return $this->setData(self::CREATOR_NAME, $name);
    }

    /**
     * Get creator email
     *
     * @return string
     */
    public function getCreatorEmail()
    {
        return $this->getData(self::CREATOR_EMAIL);
    }

    /**
     * Set creator email
     *
     * @param string $email
     * @return $this
     */
    public function setCreatorEmail($email)
    {
        return $this->setData(self::CREATOR_EMAIL, $email);
    }

    /**
     * Get creator last read reply id
     *
     * @return int
     */
    public function getCreatorLastRead()
    {
        return $this->getData(self::CREATOR_LAST_READ);
    }

    /**
     * Set creator last read reply id
     *
     * @param int $replyId
     * @return $this
     */
    public function setCreatorLastRead($replyId)
    {
        return $this->setData(self::CREATOR_LAST_READ, $replyId);
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->getData(self::SUBJECT);
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        return $this->setData(self::SUBJECT, $subject);
    }

    /**
     * Get status id
     *
     * @return int
     */
    public function getStatusId()
    {
        return $this->getData(self::STATUS_ID);
    }

    /**
     * Set status id
     *
     * @param int $statusId
     * @return $this
     */
    public function setStatusId($statusId)
    {
        return $this->setData(self::STATUS_ID, $statusId);
    }

    /**
     * Get priority
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->getData(self::PRIORITY);
    }

    /**
     * Set priority
     *
     * @param int $priority
     * @return $this
     */
    public function setPriority($priority)
    {
        return $this->setData(self::PRIORITY, $priority);
    }

    /**
     * Get remote ip address
     *
     * @return string
     */
    public function getRemoteIp()
    {
        return $this->getData(self::REMOTE_IP);
    }

    /**
     * Set remote ip address
     *
     * @param string $remoteIp
     * @return $this
     */
    public function setRemoteIp($remoteIp)
    {
        return $this->setData(self::REMOTE_IP, $remoteIp);
    }

    /**
     * Get http user agent
     *
     * @return string
     */
    public function getHttpUserAgent()
    {
        return $this->getData(self::HTTP_USER_AGENT);
    }

    /**
     * Set http user agent
     *
     * @param string $userAgent
     * @return $this
     */
    public function setHttpUserAgent($userAgent)
    {
        return $this->setData(self::HTTP_USER_AGENT, $userAgent);
    }

    /**
     * Get name of last updater
     *
     * @return string
     */
    public function getUpdaterName()
    {
        return $this->getData(self::UPDATER_NAME);
    }

    /**
     * Set name of last updater
     *
     * @param string $userName
     * @return $this
     */
    public function setUpdaterName($userName)
    {
        return $this->setData(self::UPDATER_NAME, $userName);
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

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * set updated at
     *
     * @param $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /******************************************************************/
    /* Dynamic properties
    /******************************************************************/

    /**
     * Get department object for current case
     *
     * @return \Dem\HelpDesk\Model\Department|null
     * @codeCoverageIgnore
     */
    public function getDepartment()
    {
        if ($this->getId()) {
            return $this->getResource()->getDepartment($this);
        }
        return null;
    }

    /**
     * Get case replies
     *
     * @return \Magento\Framework\Api\SearchResults
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getReplies()
    {
        if (!isset($this->replies)) {
            $this->replies = $this->getResource()->getReplies($this);
        }
        return $this->replies;
    }

    /**
     * Get initial case reply
     *
     * @param \Magento\Framework\Api\SearchResults|array
     * @return Reply|bool
     * @since 1.0.0
     */
    public function getInitialReply($replies = [])
    {
        if (empty($replies)) {
            $replies = $this->getReplies()->getItems();
        }
        if (count($replies)) {
            /** @var Reply $reply */
            foreach ($replies as $reply) {
                if ($reply->getIsInitial()) {
                    return $reply;
                }
            }
        }
        return false;
    }

    /**
     * Get case replies
     *
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getFollowers()
    {
        if (!isset($this->followers)) {
            $this->followers = $this->getResource()->getReplies($this);
        }
        return $this->followers;
    }

    /**************************************************************************/
    /**************************************************************************/

    /**
     * Get repliesToSave array
     *
     * @return \Dem\HelpDesk\Model\Reply[]
     * @since 1.0.0
     */
    public function getRepliesToSave()
    {
        return $this->repliesToSave;
    }

    /**
     * Add data to repliesToSave array
     *
     * @param Reply $reply
     * @return $this
     * @since 1.0.0
     */
    public function addReplyToSave(Reply $reply)
    {
        $this->repliesToSave[] = $reply;

        // Always set this flag to force triggering before/afterSave()
        $this->_hasDataChanges = true;

        return $this;
    }

    /**
     * Reset replies
     *
     * @return $this
     * @since 1.0.0
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
     * @return \Dem\HelpDesk\Model\Follower[]
     * @since 1.0.0
     */
    public function getFollowersToSave()
    {
        return $this->followersToSave;
    }

    /**
     * Add new follower or flag existing isDeleted
     *
     * @param Follower $follower
     * @param bool $delete Flag existing for removal
     * @return $this
     * @since 1.0.0
     */
    public function addFollowerToSave(Follower $follower, $delete = false)
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
     * @return $this
     * @since 1.0.0
     */
    public function clearFollowersToSave()
    {
        $this->followersToSave = [];
        return $this;
    }
}
