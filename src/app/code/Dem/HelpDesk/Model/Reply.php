<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Dem\HelpDesk\Model\ResourceModel\Reply as Resource;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\App\ObjectManager;

/**
 * HelpDesk Model - Reply
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class Reply extends AbstractModel
{
    const EVENT_PREFIX = 'helpdesk_reply';

    const REPLY_ID              = 'reply_id';
    const CASE_ID               = 'case_id';
    const AUTHOR_ID             = 'author_id';
    const AUTHOR_TYPE           = 'author_type';
    const REPLY_TEXT            = 'reply_text';
    const REMOTE_IP             = 'remote_ip';
    const STATUS_ID             = 'status_id';
    const IS_INITIAL            = 'is_initial';
    const CREATED_AT            = 'created_at';
    const AUTHOR_NAME           = 'author_name';

    const AUTHOR_TYPE_SYSTEM        = 'SYSTEM';
    const AUTHOR_TYPE_HELPDESK_USER = 'HELPDESK_USER';
    const AUTHOR_TYPE_CREATOR       = 'CREATOR';
    const AUTHOR_TYPE_CASE_MANAGER  = 'CASE_MANAGER';

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
     * @return \Dem\HelpDesk\Model\ResourceModel\Reply
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
        return $this->getData(self::REPLY_ID);
    }

    /**
     * Set id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData(self::REPLY_ID, $id);
    }

    /**
     * Get case id
     *
     * @return int|null
     */
    public function getCaseId()
    {
        return $this->getData(self::CASE_ID);
    }

    /**
     * Set case id
     *
     * @param int $caseId
     * @return $this
     */
    public function setCaseId($caseId)
    {
        return $this->setData(self::CASE_ID, $caseId);
    }

    /**
     * Get author id
     *
     * @return int|null
     */
    public function getAuthorId()
    {
        return $this->getData(self::AUTHOR_ID);
    }

    /**
     * Set author id
     *
     * @param int $authorId
     * @return $this
     */
    public function setAuthorId($authorId)
    {
        return $this->setData(self::AUTHOR_ID, $authorId);
    }

    /**
     * Get author type
     *
     * @return string
     */
    public function getAuthorType()
    {
        return $this->getData(self::AUTHOR_TYPE);
    }

    /**
     * Set author type
     *
     * @param string $authorType
     * @return $this
     */
    public function setAuthorType($authorType)
    {
        return $this->setData(self::AUTHOR_TYPE, $authorType);
    }

    /**
     * Set author name
     *
     * This value is set dynamically on load,
     * it cannot be set here
     *
     * @param string $authorName
     * @return $this
     */
    public function setAuthorName($authorName)
    {
        return $this;
    }

    /**
     * Get case number
     *
     * @return string
     */
    public function getAuthorName()
    {
        if (!$this->hasData(self::AUTHOR_NAME)) {
            $this->getResource()->setAuthorName($this);
        }
        return $this->getData(self::AUTHOR_NAME);
    }

    /**
     * Get reply text
     *
     * @return string
     */
    public function getReplyText()
    {
        return $this->getData(self::REPLY_TEXT);
    }

    /**
     * Set reply text
     *
     * @param string $replyText
     * @return $this
     */
    public function setReplyText($replyText)
    {
        return $this->setData(self::REPLY_TEXT, $replyText);
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
     * Get is_initial value
     *
     * @return bool
     */
    public function getIsInitial()
    {
        return (bool) $this->getData(self::IS_INITIAL);
    }

    /**
     * Set status id
     * This value is only set programatically.
     *
     * @param int|bool $isInitial
     * @return $this
     */
    public function setIsInitial($isInitial = false)
    {
        return $this;
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

    /**************************************************************************/
    /**************************************************************************/

    /**
     * Check if reply author is type AUTHOR_TYPE_SYSTEM
     *
     * @return bool
     */
    public function getIsAuthorTypeSystem()
    {
        return ($this->getAuthorType() === self::AUTHOR_TYPE_SYSTEM);
    }

    /**
     * Check if reply author is type AUTHOR_TYPE_CREATOR
     *
     * @return bool
     */
    public function getIsAuthorTypeCreator()
    {
        return ($this->getAuthorType() === self::AUTHOR_TYPE_CREATOR);
    }

    /**
     * Check if reply author is type AUTHOR_TYPE_HELPDESK_USER
     *
     * @return bool
     */
    public function getIsAuthorTypeUser()
    {
        return ($this->getAuthorType() === self::AUTHOR_TYPE_HELPDESK_USER);
    }

    /**
     * Check if reply author is type AUTHOR_TYPE_HELPDESK_USER
     *
     * @return bool
     */
    public function getIsAuthorTypeCaseManager()
    {
        return ($this->getAuthorType() === self::AUTHOR_TYPE_CASE_MANAGER);
    }

}
