<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Model\AbstractModel;
use Dem\HelpDesk\Api\Data\ReplyInterface;

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
class Reply extends AbstractModel implements ReplyInterface
{
    const EVENT_PREFIX = 'helpdesk_reply';

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
        $this->_init(\Dem\HelpDesk\Model\ResourceModel\Reply::class);
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(ReplyInterface::REPLY_ID);
    }

    /**
     * Get author id
     *
     * @return int|null
     */
    public function getAuthorId()
    {
        return $this->getData(ReplyInterface::AUTHOR_ID);
    }

    /**
     * Set author id
     *
     * @param int $authorId
     * @return ReplyInterface
     */
    public function setAuthorId($authorId)
    {
        return $this->setData(ReplyInterface::AUTHOR_ID, $authorId);
    }

    /**
     * Get author type
     *
     * @return string
     */
    public function getAuthorType()
    {
        return $this->getData(ReplyInterface::AUTHOR_TYPE);
    }

    /**
     * Set author type
     *
     * @param string $authorType
     * @return ReplyInterface
     */
    public function setAuthorType($authorType)
    {
        return $this->setData(ReplyInterface::AUTHOR_TYPE, $authorType);
    }

    /**
     * Get reply text
     *
     * @return string
     */
    public function getReplyText()
    {
        return $this->getData(ReplyInterface::REPLY_TEXT);
    }

    /**
     * Set reply text
     *
     * @param string $replyText
     * @return ReplyInterface
     */
    public function setReplyText($replyText)
    {
        return $this->setData(ReplyInterface::REPLY_TEXT, $replyText);
    }

    /**
     * Get remote ip address
     *
     * @return string
     */
    public function getRemoteIp()
    {
        return $this->getData(ReplyInterface::REMOTE_IP);
    }

    /**
     * Set remote ip address
     *
     * @param string $remoteIp
     * @return ReplyInterface
     */
    public function setRemoteIp($remoteIp)
    {
        return $this->setData(ReplyInterface::REMOTE_IP, $remoteIp);
    }

    /**
     * Get status id
     *
     * @return int
     */
    public function getStatusId()
    {
        return $this->getData(ReplyInterface::STATUS_ID);
    }

    /**
     * Set status id
     *
     * @param int $statusId
     * @return ReplyInterface
     */
    public function setStatusId($statusId)
    {
        return $this->setData(ReplyInterface::STATUS_ID, $statusId);
    }

    /**
     * Get status id
     *
     * @return int
     */
    public function getIsInitial()
    {
        return (bool) $this->getData(ReplyInterface::IS_INITIAL);
    }

    /**
     * Set status id
     *
     * @param int $isInitial
     * @return ReplyInterface
     */
    public function setIsInitial($isInitial)
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
        return $this->getData(ReplyInterface::CREATED_AT);
    }

    /**
     * set created at
     *
     * @param $createdAt
     * @return ReplyInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(ReplyInterface::CREATED_AT, $createdAt);
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
        return ($this->getAuthorType() === ReplyInterface::AUTHOR_TYPE_SYSTEM);
    }

    /**
     * Check if reply author is type AUTHOR_TYPE_CREATOR
     *
     * @return bool
     */
    public function getIsAuthorTypeCreator()
    {
        return ($this->getAuthorType() === ReplyInterface::AUTHOR_TYPE_CREATOR);
    }

    /**
     * Check if reply author is type AUTHOR_TYPE_HELPDESK_USER
     *
     * @return bool
     */
    public function getIsAuthorTypeUser()
    {
        return ($this->getAuthorType() === ReplyInterface::AUTHOR_TYPE_HELPDESK_USER);
    }

    /**
     * Check if reply author is type AUTHOR_TYPE_HELPDESK_USER
     *
     * @return bool
     */
    public function getIsAuthorTypeCaseManager()
    {
        return ($this->getAuthorType() === ReplyInterface::AUTHOR_TYPE_CASE_MANAGER);
    }

}
