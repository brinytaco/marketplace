<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Dem\HelpDesk\Model\ResourceModel\DepartmentUser as Resource;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

/**
 * HelpDesk Model - DepartmentUser
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class DepartmentUser extends AbstractModel
{
    const EVENT_PREFIX = 'helpdesk_department_user';

    const DEPT_USER_ID          = 'dept_user_id';
    const DEPARTMENT_ID         = 'department_id';
    const USER_ID               = 'user_id';
    const IS_FOLLOWER           = 'is_follower';
    const CREATED_AT            = 'created_at';
    const UPDATED_AT            = 'updated_at';

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
        return $this->getData(self::DEPT_USER_ID);
    }

    /**
     * Get Department id
     *
     * @return int
     */
    public function getDepartmentId()
    {
        return $this->getData(self::DEPARTMENT_ID);
    }

    /**
     * Set Department id
     *
     * @param int $departmentId
     * @return DepartmentUser
     */
    public function setDepartmentId($departmentId)
    {
        return $this->setData(self::DEPARTMENT_ID, $departmentId);
    }

    /**
     * Get User id
     *
     * @return string|null
     */
    public function getUserId()
    {
        return $this->getData(self::USER_ID);
    }

    /**
     * Set User id
     *
     * @param int $userId
     * @return DepartmentUser
     */
    public function setUserId($userId)
    {
        return $this->setData(self::USER_ID, $userId);
    }

    /**
     * Get is follower flag
     *
     * @return string|null
     */
    public function getIsFollower()
    {
        return $this->getData(self::IS_FOLLOWER);
    }

    /**
     * Set is follower flag
     *
     * @param bool $isFollower
     * @return DepartmentUser
     */
    public function setIsFollower($isFollower)
    {
        return $this->setData(self::IS_FOLLOWER, $isFollower);
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
     * @return DepartmentUser
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
     * @return DepartmentUser
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

}
