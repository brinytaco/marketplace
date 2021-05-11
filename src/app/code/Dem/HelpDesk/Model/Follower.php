<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Model\AbstractModel;
use Dem\HelpDesk\Api\Data\FollowerInterface;

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
class Follower extends AbstractModel implements FollowerInterface
{
    const EVENT_PREFIX = 'helpdesk_follower';

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
        $this->_init(\Dem\HelpDesk\Model\ResourceModel\Follower::class);
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(FollowerInterface::FOLLOWER_ID);
    }

    /**
     * Get case id
     *
     * @return int|null
     */
    public function getCaseId()
    {
        return $this->getData(FollowerInterface::CASE_ID);
    }

    /**
     * Set case id
     *
     * @param int $caseId
     * @return FollowerInterface
     */
    public function setCaseId($caseId)
    {
        return $this->setData(FollowerInterface::CASE_ID, $caseId);
    }

    /**
     * Get user id
     *
     * @return int|null
     */
    public function getUserId()
    {
        return $this->getData(FollowerInterface::USER_ID);
    }

    /**
     * Set user id
     *
     * @param int $userId
     * @return FollowerInterface
     */
    public function setUserId($userId)
    {
        return $this->setData(FollowerInterface::USER_ID, $userId);
    }

    /**
     * Get last read reply id
     *
     * @return int|null
     */
    public function getLastRead()
    {
        return $this->getData(FollowerInterface::LAST_READ);
    }

    /**
     * Set last read reply id
     *
     * @param int $lastRead
     * @return FollowerInterface
     */
    public function setLastRead($lastRead)
    {
        return $this->setData(FollowerInterface::LAST_READ, $lastRead);
    }

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(FollowerInterface::CREATED_AT);
    }

    /**
     * set created at
     *
     * @param $createdAt
     * @return FollowerInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(FollowerInterface::CREATED_AT, $createdAt);
    }

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(FollowerInterface::UPDATED_AT);
    }

    /**
     * set updated at
     *
     * @param $updatedAt
     * @return FollowerInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(FollowerInterface::UPDATED_AT, $updatedAt);
    }

}
