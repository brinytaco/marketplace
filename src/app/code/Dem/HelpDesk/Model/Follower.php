<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Dem\HelpDesk\Model\ResourceModel\Follower as Resource;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

/**
 * HelpDesk Model - Follower
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class Follower extends AbstractModel
{
    const EVENT_PREFIX = 'helpdesk_follower';

    const FOLLOWER_ID         = 'follower_id';
    const CASE_ID             = 'case_id';
    const USER_ID             = 'user_id';
    const LAST_READ           = 'last_read';
    const CREATED_AT          = 'created_at';
    const UPDATED_AT          = 'updated_at';

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
        $this->_init(Resource::class);
    }

    /**
     * Get resource instance
     *
     * @throws LocalizedException
     * @return Resource
     */
    protected function _getResource()
    {
        return parent::_getResource();
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::FOLLOWER_ID);
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
     * @return Follower
     */
    public function setCaseId($caseId)
    {
        return $this->setData(self::CASE_ID, $caseId);
    }

    /**
     * Get user id
     *
     * @return int|null
     */
    public function getUserId()
    {
        return $this->getData(self::USER_ID);
    }

    /**
     * Set user id
     *
     * @param int $userId
     * @return Follower
     */
    public function setUserId($userId)
    {
        return $this->setData(self::USER_ID, $userId);
    }

    /**
     * Get last read reply id
     *
     * @return int|null
     */
    public function getLastRead()
    {
        return $this->getData(self::LAST_READ);
    }

    /**
     * Set last read reply id
     *
     * @param int $lastRead
     * @return Follower
     */
    public function setLastRead($lastRead)
    {
        return $this->setData(self::LAST_READ, $lastRead);
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
     * @return Follower
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
     * @return Follower
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

}
